<?php

return function () {
    return [
        [
            'pattern' => 'listings/(:all)/(:all)',
            'method' => 'GET',
            'action' => function ($language, $path) {
                $content = [];
                $breadcrumbs = [];
                $getData = $path !== 'site' ? true : false;
                $data = $getData ? page($path) : site();


                if ($data->hasChildren()) {
                    if ($getData) {
                        foreach ($data->children()->first()->parents()->flip() as $parent) {
                            array_push($breadcrumbs,[
                                'id' => $parent->id(),
                                'title' => $parent->content($language)->title()->value()
                            ]);
                        }
                    }

                    foreach ($data->children() as $item) {
                        array_push($content, [
                            'id' => $item->id(),
                            'url' => $item->url($language),
                            'text' => $item->content($language)->title()->value(),
                            'count' => $item->index()->count(),
                            'children' => []
                        ]);
                    }
                }
                return [
                    'content' => $content,
                    'breadcrumbs' => $breadcrumbs,
                ];
            }
        ],
    ];
};
