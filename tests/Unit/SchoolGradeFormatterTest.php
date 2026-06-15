<?php

namespace Tests\Unit;

use App\Support\SchoolGradeFormatter;
use PHPUnit\Framework\TestCase;

class SchoolGradeFormatterTest extends TestCase
{
    public function test_it_adds_the_degree_symbol_to_single_grades_and_ranges(): void
    {
        $this->assertSame('5° grado', SchoolGradeFormatter::format('5 grado'));
        $this->assertSame('1° a 6° grado', SchoolGradeFormatter::format('1 a 6 grado'));
        $this->assertSame('10° a 12° grado', SchoolGradeFormatter::format('10° a 12 grado'));
        $this->assertSame('Lista de 3° grado', SchoolGradeFormatter::format('Lista de 3º grado'));
    }
}
