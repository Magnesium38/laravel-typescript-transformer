<?php

use Spatie\LaravelTypeScriptTransformer\Actions\GenerateControllerSupportAction;
use Spatie\TypeScriptTransformer\Data\WritingContext;
use Spatie\TypeScriptTransformer\Transformed\Transformed;

it('generates controller support', function () {
    $support = (new GenerateControllerSupportAction())->execute();

    $output = implode("\n", array_map(
        fn (Transformed $item) => $item->write(new WritingContext([])),
        $support,
    ));

    expect($output)->toMatchSnapshot();
});
