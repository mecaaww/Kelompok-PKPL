<?php

function sourceCode(string $relativePath): string
{
    $path = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($path)) {
        throw new RuntimeException("File source tidak ditemukan: {$relativePath}");
    }

    $source = file_get_contents($path);

    if ($source === false) {
        throw new RuntimeException("File source tidak dapat dibaca: {$relativePath}");
    }

    return $source;
}

function hasDocblockFor(string $source, string $method): bool
{
    return preg_match(
        '/\/\*\*[\s\S]*?\*\/\s*public function '.preg_quote($method, '/').'\s*\(/',
        $source,
    ) === 1;
}
