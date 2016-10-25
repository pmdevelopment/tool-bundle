# pm-table

## Template

```html
    {% block body %}
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject font-green bold uppercase">{{ block("title") }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-xs-7">
                        <div class="dropdown pull-left hidden">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Filter {{ "filter"|icon }}</button>
                            <ul class="dropdown-menu pm-table-filter"></ul>
                        </div>
                        <div class="pm-table-filter-selected pull-left"></div>
                    </div>
                    <div class="col-xs-5">
    
                    </div>
                </div>
                <br/>
    
                <table class="table table-hover table-vmiddle pm-table">
                    <thead>
                    <tr>
                        <th data-key="name" data-sortable="true">Name</th>
                        <th data-key="status" data-sortable="true" data-filter="true">Status</th>
                        <th data-key="created" data-sortable="true">Erstellt</th>
                        <th data-key="project" data-filter="true">Projekt</th>
                        <th data-key="category" data-filter="true">Kategorie</th>
                        <th>API</th>
                        {% if is_granted("ROLE_FUNCTION_MANAGER") %}
                            <th class="text-right">Optionen</th>
                        {% endif %}
                    </tr>
                    </thead>
                    <tbody>
                    {% for domain in pagination %}
                        <tr data-id="{{ domain.id }}">
                            <td><a href="{{ object_path("show",domain) }}">{{ domain }}</a></td>
                            <td>{{ domain.statusText }}</td>
                            <td>{{ domain.created|date('d.m.Y') }}</td>
                            <td>
                                {% if domain.project %}
                                    {{ domain.project }}
                                {% else %}
                                    {{ "times text-danger"|icon }}
                                {% endif %}
                            </td>
                            <td>
                                {% if domain.category %}
                                    {{ domain.category }}
                                {% else %}
                                    {{ "times text-danger"|icon }}
                                {% endif %}
                            </td>
                            <td>
                                {% if domain.apiAccount %}
                                    {{ "check text-success"|icon }}
                                {% else %}
                                    {{ "times text-danger"|icon }}
                                {% endif %}
                            </td>
                            {% if is_granted("ROLE_FUNCTION_MANAGER") %}
                                <td class="text-right">
                                    {% include "PMCoreLayoutBundle:Components:button_edit.html.twig" with {"path": object_path("edit", domain) } %}
                                </td>
                            {% endif %}
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
    
                {% include "PMCoreLayoutBundle::pagination.html.twig" with {'pagination':pagination, "limit":true} %}
    
            </div>
        </div>
    {% endblock %}
    
    {% block javascript %}
        {% if is_granted("ROLE_FUNCTION_MANAGER") %}
            <script type="text/javascript">
                $(document).ready(function () {
    
                    $('.pm-table').pmTable({
                        modules: {
                            action: true,
                            sortable: true,
                            limitable: true,
                            filter: true
                        },
                        paths: {
                            self: "{{ path(app.request.get('_route'), app.request.get('_route_params')) }}",
                            action: "{{ path("pm_core_domain_index_editmultiple") }}",
                            filter: "{{ path("pm_domain_manage_default_indexfilter") }}"
                        },
                        sorting: {
                            index: "{{ table.sorting.index }}",
                            direction: "{{ table.sorting.direction }}"
                        },
                        limit: {
                            value: {{ table.limit }},
                            select: 'select.pm-pagination-limit'
                        },
                        filter: {
                            selectors: {
                                menu: 'ul.pm-table-filter',
                                labels: '.pm-table-filter-selected'
                            },
                            active: {{ table.filtersJson|raw }}
                        }
                    });
    
                });
            </script>
        {% endif %}
    {% endblock %}
```

## Controller

```php
    use JavaScriptTableTrait;
    use SerializationTrait;

    /**
     * Filters
     *
     * @var array
     */
    private $filters = [
        'status',
        'project',
        'category'
    ];

    /**
     * Index
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $table = $this->loadTableFromSession($request);

        if (null === $table) {
            $sorting = new SortingModel('created', 'desc');
            $sorting
                ->setSortableFields(
                    [
                        'name',
                        'status',
                        'created',
                    ]
                );

            $table = new TableModel($sorting, 100);
        }


        $dataQb = $this->getDoctrine()->getRepository("PMCoreDomainBundle:Domain")->createQueryBuilder('domain');
        $dataQb
            ->where('domain.deleted = 0');

        $filters = $request->query->get('filter', []);
        foreach ($filters as $filterKey => $filterValues) {
            if (false === in_array($filterKey, $this->filters)) {
                continue;
            }

            $table->resetFilter($filterKey);
            $filterArray = explode(",", $filterValues);

            if ('status' === $filterKey) {
                $statusValid = DomainStatusHelper::getStatusArray();
                foreach ($filterArray as $filterArrayIndex => $status) {
                    if (false === isset($statusValid[$status])) {
                        unset($filterArray[$filterArrayIndex]);

                        continue;
                    }

                    $table->addFilter($filterKey, new FilterItemModel($status, $statusValid[$status]));
                }
            } elseif ('project' === $filterKey) {
                foreach ($filterArray as $filterArrayIndex => $projectId) {
                    $project = $this->getDoctrine()->getRepository('PMCoreProjectBundle:Project')->find(intval($projectId));
                    if (null === $project) {
                        unset($filterArray[$filterArrayIndex]);

                        continue;
                    }

                    $table->addFilter($filterKey, new FilterItemModel($project->getId(), $project->getName()));
                }

            } elseif ('category' === $filterKey) {
                foreach ($filterArray as $filterArrayIndex => $categoryId) {
                    $category = $this->getDoctrine()->getRepository('PMCoreDomainBundle:Category')->find(intval($categoryId));
                    if (null === $category) {
                        unset($filterArray[$filterArrayIndex]);

                        continue;
                    }

                    $table->addFilter($filterKey, new FilterItemModel($category->getId(), $category->getTitle()));
                }

                if (0 < count($filterArray)) {
                    $dataQb->andWhere($dataQb->expr()->in('domain.category', $filterArray));
                }
            }
        }

        foreach ($table->getFilters() as $filterKey => $filterItems) {
            if (false === is_array($filterItems) || 0 === count($filterItems)) {
                continue;
            }

            $dataQb->andWhere($dataQb->expr()->in(sprintf('domain.%s', $filterKey), CollectionUtility::getIds($filterItems)));
        }

        $this->addOrderBy($dataQb, $request, $table);
        $this->addLimit($dataQb, $request, $table);

        $this->saveTableToSession($request, $table);

        /**
         * Environments
         */
        $this->getEnvironmentService()->extendQueryBuilder($dataQb);
        $this->getProjectService()->extendQueryBuilderWithWhitelist($dataQb, 'project');

        $pagination = $this->get("knp_paginator")->paginate($dataQb, $request->query->get('page', 1), $table->getLimit());

        return [
            'pagination' => $pagination,
            'table'      => $table
        ];
    }

    /**
     * Index
     *
     * @param Request $request
     *
     * @return array
     *
     * @Route("/filter.json")
     * @Method("GET")
     */
    public function indexFilterAction(Request $request)
    {
        $key = $request->query->get('key');
        if (false === in_array($key, $this->filters)) {
            throw $this->createNotFoundException(sprintf('Unknown key %s', $key));
        }

        $searchTerm = $request->query->get('q');
        $searchPage = $request->query->getInt('page', 1);
        $searchLimit = $request->query->getInt('limit', 30);

        $result = new FilterResultModel();

        if ('status' === $key) {
            $statusArray = DomainStatusHelper::getStatusArray();
            foreach ($statusArray as $index => $text) {
                $result->addItem(new FilterItemModel($index, $text));
            }

            $result->setTotalCount(count($statusArray));
        } elseif ('project' === $key) {
            $projects = $this->getDoctrine()->getRepository('PMCoreProjectBundle:Project')->findBySearchTerm($searchTerm, $this->getProjectService(), $searchPage, $searchLimit);
            foreach ($projects as $project) {
                $result->addItem(new FilterItemModel($project->getId(), $project->getName()));
            }
        } elseif ('category' === $key) {
            $categories = $this->getDoctrine()->getRepository('PMCoreDomainBundle:Category')->findBySearchTerm($searchTerm, $searchPage, $searchLimit);
            foreach ($categories as $category) {
                $result->addItem(new FilterItemModel($category->getId(), $category->getTitle()));
            }
        }


        return $this->getSerializedJsonResponse($result);
    }
```