# pm-select2

## Form

Add class "pm-select2" for Select fields. To use ajax results add data-path="/your/path". Example with entity:

```php
    ->add('movies', EntityType::class, [
        'label'         => 'Filme:',
        'class'         => Movie::class,
        'query_builder' => Movie::getFormQueryBuilder($post->getMovies()),
        'multiple'      => true,
        'attr'          => [
            'class'     => 'pm-select2',
            'data-path' => $options['path_select2_movies'],
        ]
    ])
    ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
        $data = $event->getData();
    
        if (false === isset($data['movies']) || 0 === count($data['movies'])) {
            return;
        }
    
        $event->getForm()->add('movies', EntityType::class, [
            'label'         => 'Filme:',
            'class'         => Movie::class,
            'query_builder' => Movie::getFormQueryBuilder($data['movies']),
            'multiple'      => true,
            'attr'          => [
                'class'     => 'pm-select2',
                'data-path' => $options['path_select2_movies'],
            ]
        ]);
    })
```

## Template

Enable the module to use the default handler:

```html
<script type="text/javascript">
    pmUtil.config.module.select2.enabled = true;
</script>
```

## Ajax

Some example action to get ajax results:

```php
    public function moviesAction(Request $request)
    {
        $request = new Select2RequestModel($request);
        $response = new Select2ResponseModel();

        if (3 > $request->getQueryLength()) {
            return $response->getJsonResponse();
        }

        $movies = $this->getDoctrine()->getRepository('PMMovieBundle:Movie')->createQueryBuilder('movie');
        $movies
            ->where('movie.deleted = 0')
            ->andWhere($movies->expr()->like('movie.title', ':query'))
            ->setParameter('query', sprintf('%%%s%%', $request->getQuery()))
            ->orderBy('movie.title', 'asc');

        $request->extendQueryBuilder($movies);

        /** @var Movie $movie */
        foreach ($movies->getQuery()->getResult() as $movie) {
            $item = new Select2ItemModel($movie->getId(), $movie->getTitle());

            $response->addItem($item);
        }

        $response->setTotalCountByQueryBuilder($movies);

        return $response->getJsonResponse();
    }
```