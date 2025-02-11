<?php

namespace Notion\Blocks\Renderer\Markdown;

use Notion\Blocks\BlockInterface;
use Notion\Blocks\Image;
use Notion\Blocks\Renderer\BlockRendererInterface;
use Notion\Blocks\Renderer\MarkdownRenderer;

final class ImageRenderer implements BlockRendererInterface
{
    public static function render(BlockInterface $block, int $depth = 0): string
    {
        if (!$block instanceof Image) {
            return "";
        }

        $url = $block->file->url;

        $caption = $alt = '';
        foreach ($block->file->caption as $v) {
            $caption .= $v->toString();
            $alt .= self::removeFigurePrefix($v->toString());
        }

        return MarkdownRenderer::ident("![$alt]($url)\n$caption\n", $depth);
    }

    private static function removeFigurePrefix(string $text): string {
        return preg_replace('/^▲ Fig \d+ - /', '', $text);
    }
}
