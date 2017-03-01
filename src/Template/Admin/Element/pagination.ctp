<div class="row">

                <div class="col-sm-6 pagination">
                    <?php
                    // Change a template
                    $config = [
                        'nextActive' => '<li class="next"><a rel="next" href="{{url}}">{{text}}</a></li>',
                        'nextDisabled' => '<li class="next disabled"><span>{{text}}</span></li>',
                        'prevActive' => '<li class="prev"><a rel="prev" href="{{url}}">{{text}}</a></li>',
                        'prevDisabled' => '<li class="prev disabled"><span>{{text}}</span></li>',
                        'counterRange' => '{{start}} - {{end}} of {{count}}',
                        'counterPages' => '{{page}} of {{pages}}',
                        'first' => '<li class="first"><a href="{{url}}">{{text}}</a></li>',
                        'last' => '<li class="last"><a href="{{url}}">{{text}}</a></li>',
                        'number' => '<li><a href="{{url}}">{{text}}</a></li>',
                        'current' => '<li class="active"><span>{{text}}</span></li>',
                        'ellipsis' => '<li class="ellipsis"><a href="#">...</a></li>',
                        'sort' => '<a href="{{url}}">{{text}}</a>',
                        'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
                        'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
                        'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
                        'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
                    ];
                    $this->Paginator->templates($config);

                    echo $this->Paginator->counter(
                            'Page {{page}} of {{pages}}, showing {{current}} records out of
     {{count}} total'
                    );
                    //, starting on record {{start}}, ending on {{end}}
                    ?>
                </div>
                <div class="col-sm-6 text-right">
                    <ul class="pagination">
                        <?= $this->Paginator->prev('<i class="fa fa-fw fa-chevron-left"></i>', ['escape' => false]) ?>
                        <?= $this->Paginator->numbers(['first' => 2, 'last' => 2, 'before' => false, 'after' => false, 'modulus' => 6]) ?>
                        <?= $this->Paginator->next('<i class="fa fa-fw fa-chevron-right"></i>', ['escape' => false]) ?>
                    </ul>
                </div>
            </div>