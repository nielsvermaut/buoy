<?php

namespace Buoy\Service;

class ParameterService
{
    public function replaceParameterInFile(string $path, string $parameter, string $value): void
    {
        $filePath = getcwd() . $path;

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException(
                'The given path %s does not exist. Looked in %s',
                $path,
                $filePath
            );
        }

        $contents = file_get_contents($filePath);

        $contents = str_replace(sprintf('{%% %s %%}', $parameter), $value, $contents);
        $contents = str_replace(sprintf('{%%%s%%}', $parameter), $value, $contents);

        file_put_contents($filePath, $contents);
    }
}
