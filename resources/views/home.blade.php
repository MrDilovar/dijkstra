<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tojir.loc</title>

    <!-- Favicons -->
    <link rel="shortcut icon" href="/img/favicon.ico">

    <link href="/lib/normalize-8.0.1.css" rel="stylesheet">
    <link href="/lib/bootstrap-4.3.1.min.css" rel="stylesheet">
</head>
<body>
<!--[if lte IE 9]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
<![endif]-->

<div class="container py-3">
    <h2 class="text-center">Алгоритм Дейкстры</h2>
    <form class="mt-4" action="/" method="get">
        <div class="row" id="listener">
            <div class="col-auto">
                <h5>Вершины</h5>
                <div id="points">
                    @empty($graph)
                        <div class="form-group">
                            <input name="points[]" class="form-control form-control-sm w-25" type="text">
                        </div>
                    @else
                        @foreach($graph as $key => $value)
                            <div class="form-group">
                                <input name="points[]" value="{{$key}}" class="form-control form-control-sm w-25" type="text">
                            </div>
                        @endforeach
                        <script>
                            var targetContainerid = {{ count($graph) - 1 }};
                        </script>
                    @endempty
                </div>
                <hr>
                <button id="addPoints" type="button" class="btn btn-sm btn-outline-primary">Добавить</button>
            </div>
            <div class="col-auto">
                <h5>Длина пути до вершины</h5>
                <div id="targetContainer">
                    @empty($graph)
                        <div class="form-group form-row">
                            <div class="row targets" id="tar0">
                                <div class="col-auto row no-gutters">
                                    <div class="col-auto">
                                        <input name="targets[0][tar][]" class="form-control form-control-sm" style="width: 40px;" type="text">
                                    </div>
                                    <div class="mx-2">:</div>
                                    <div class="col-auto">
                                        <input name="targets[0][val][]" class="form-control form-control-sm" style="width: 40px;" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto ml-2">
                                <button type="button" data-id="0" class="btn btn-sm btn-outline-primary addTarget">Добавить</button>
                            </div>
                        </div>
                    @else
                        @foreach($graph as $key => $value)
                            <div class="form-group form-row">
                                <div class="row targets" id="tar{{ $loop->index }}">
                                    @foreach($value as $tar => $val)
                                        <div class="col-auto row no-gutters">
                                            <div class="col-auto">
                                                <input name="targets[{{ $loop->parent->index }}][tar][]" value="{{ $tar }}" class="form-control form-control-sm" style="width: 40px;" type="text">
                                            </div>
                                            <div class="mx-2">:</div>
                                            <div class="col-auto">
                                                <input name="targets[{{ $loop->parent->index }}][val][]" value="{{ $val }}" class="form-control form-control-sm" style="width: 40px;" type="text">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-auto ml-2">
                                    <button type="button" data-id="{{ $loop->index }}" class="btn btn-sm btn-outline-primary addTarget">Добавить</button>
                                </div>
                            </div>
                        @endforeach
                    @endempty
                </div>
            </div>
        </div>
        <div class="mt-3 form-row">
            <div class="col-auto">
                <input type="text" name="from" value="{{ request()->from }}" placeholder="От Вершины" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <input type="text" name="to" value="{{ request()->to }}" placeholder="До Вершины" class="form-control form-control-sm">
            </div>
        </div>
        <div>
            <hr>
            <button type="submit" class="btn btn-sm btn-outline-primary">Вычислить</button>
            <a href="/" class="btn btn-sm btn-outline-danger">Очистить</a>
        </div>
    </form>
    @isset($dijkstra->points)
        <div>
            <h5 class="mt-3">Результат</h5>
            <div>Длина пути от {{ $dijkstra->points[0] }} до {{ $dijkstra->points[count($dijkstra->points)-1] }}: {{ $dijkstra->dist }}</div>
            <div>
                <span>Вершины от {{ $dijkstra->points[0] }} до {{ $dijkstra->points[count($dijkstra->points)-1] }}:</span>
                <span>
                @foreach($dijkstra->points as $point)
                        {{$point}}
                    @endforeach
            </span>
            </div>
        </div>
    @else
        @if(request()->has('from'))
            Данные некоректный.
        @endif
    @endisset
</div>

<!-- Scripts -->
<script src="/lib/jquery-3.4.1.min.js"></script>
<script src="/lib/popper-1.14.6.min.js"></script>
<script src="/lib/bootstrap-4.3.1.min.js"></script>
<script>
    var targetContainerid = targetContainerid || 0;

    $('#listener').click(function () {
        let target = $(event.target);

        if(target.hasClass('addTarget')) {
            let id = target.data().id;
            let tar = '#tar' + id;

            let targets = $(tar + '.targets');

            targets.append($('<div class="col-auto row no-gutters"><div class="col-auto"><input name="targets[' + id + '][tar][]" class="form-control form-control-sm" style="width: 40px;" type="text"></div><div class="mx-2">:</div><div class="col-auto"><input name="targets[' + id + '][val][]" class="form-control form-control-sm" style="width: 40px;" type="text"></div></div>'));
        }
    });

    let addPoints = $('#addPoints');
    let points = $('#points');
    addPoints.click(function () {
        points.append($($('<div class="form-group"><input name="points[]" class="form-control form-control-sm w-25" type="text"></div>')));

        targetContainerid = targetContainerid + 1;
        $('#targetContainer').append('<div class="form-group form-row"><div class="row targets" id="tar' + targetContainerid + '"><div class="col-auto row no-gutters"><div class="col-auto"><input name="targets[' + targetContainerid + '][tar][]" class="form-control form-control-sm" style="width: 40px;" type="text"></div><div class="mx-2">:</div><div class="col-auto"><input name="targets[' + targetContainerid + '][val][]" class="form-control form-control-sm" style="width: 40px;" type="text"></div></div></div><div class="col-auto ml-2"><button type="button" data-id="' + targetContainerid + '" class="btn btn-sm btn-outline-primary addTarget">Добавить</button></div></div>');
    });
</script>
</body>
</html>