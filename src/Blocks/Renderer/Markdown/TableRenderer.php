<?php

namespace Notion\Blocks\Renderer\Markdown;

use Notion\Blocks\BlockInterface;
use Notion\Blocks\Renderer\BlockRendererInterface;
use Notion\Blocks\Renderer\MarkdownRenderer;
use Notion\Blocks\Table;
use Notion\Blocks\TableRow;

final class TableRenderer implements BlockRendererInterface
{
    public static function render(BlockInterface $block, int $depth = 0): string
    {
        if (!$block instanceof Table) {
            return "";
        }

        $markdown = "";
        $rows = $block->rows;

        if (empty($rows)) {
            return $markdown;
        }

        $header = self::renderRow($rows[0]);
        $markdown .= $header . "\n";
        $markdown .= str_repeat("| --- ", count($rows[0]->cells)) . "|\n";

        for ($i = 1; $i < count($rows); $i++) {
            $markdown .= self::renderRow($rows[$i]) . "\n";
        }

        return MarkdownRenderer::ident($markdown, $depth);
    }

    private static function renderRow(TableRow $row): string
    {
        $cells = array_map(fn ($cell) => RichTextRenderer::render(...$cell), $row->cells);
        return "| " . implode(" | ", $cells) . " |";
    }
}
