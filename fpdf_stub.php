<?php
// IDE/static analysis helper; not autoloaded at runtime.
if (!class_exists('FPDF')) {
    /**
     * Lightweight stub declaring methods used by the project.
     *
     * The real implementation comes from `setasign/fpdf` when
     * Composer loads its files. Defining this class only when the
     * real one is missing prevents conflicts and the fatal error
     * you encountered earlier.
     *
     * @method void AddPage()
     * @method int setSourceFile(string $file)
     * @method int importPage(int $page)
     * @method void useTemplate($tpl)
     * @method void SetFont(string $family, string $style = '', float $size = 0)
     * @method void Text(float $x, float $y, string $txt)
     * @method void SetXY(float $x, float $y)
     * @method void Cell(float $w, float $h, string $txt = '', int $border = 0, int $ln = 0)
     * @method void Output(string $dest = '', string $name = '', bool $isUTF8 = false)
     */
    class FPDF {}
}
