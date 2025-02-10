<?php

namespace Notion\Test\Unit\Blocks\Renderer\Markdown;

use Notion\Blocks\Renderer\Markdown\TableRenderer;
use Notion\Blocks\Table;
use Notion\Blocks\TableRow;
use Notion\Common\RichText;
use PHPUnit\Framework\TestCase;

class TableRendererTest extends TestCase
{
    public function test_render_with_column_header(): void
    {
        $data = [
            ['A1', 'A2', 'A3'],
            ['B1', 'B2', 'B3'],
        ];

        $block = Table::create()->changeWidth(3)
            ->enableColumnHeader()
            ->addRow($this->createRow(...$data[0]))
            ->addRow($this->createRow(...$data[1]));

        $markdown = TableRenderer::render($block);

        $expected = <<<MARKDOWN
| A1 | A2 | A3 |
| --- | --- | --- |
| B1 | B2 | B3 |

MARKDOWN;

        $this->assertSame($expected, $markdown);
    }

    public function test_render_without_column_header(): void
    {
        $data = [
            ['A1', 'A2', 'A3'],
            ['B1', 'B2', 'B3'],
        ];

        $block = Table::create()->changeWidth(3)
            ->disableColumnHeader()
            ->addRow($this->createRow(...$data[0]))
            ->addRow($this->createRow(...$data[1]));

        $markdown = TableRenderer::render($block);

        $expected = <<<MARKDOWN
| A1 | A2 | A3 |
| B1 | B2 | B3 |

MARKDOWN;

        $this->assertSame($expected, $markdown);
    }

    public function test_render_with_rich_text(): void
    {
        $data = [
            ['**A1**', 'A2', 'A3'],
            ['**B1**', 'B2', 'B3'],
        ];

        $block = Table::create()->changeWidth(3)
            ->disableColumnHeader()
            ->addRow($this->createRow(...$data[0]))
            ->addRow($this->createRow(...$data[1]));

        $markdown = TableRenderer::render($block);

        $expected = <<<MARKDOWN
| **A1** | A2 | A3 |
| **B1** | B2 | B3 |

MARKDOWN;

        $this->assertSame($expected, $markdown);
    }

    private function createRow(string ...$columns): TableRow
    {
        $row = TableRow::create();
        foreach ($columns as $column) {
            $row = $row->addCell(RichText::fromString($column));
        }
        return $row;
    }
}
