<?php
declare(strict_types=1);

namespace Fastknife\Domain\Vo;
use Intervention\Image\Image;
class TemplateVo extends ImageVo
{
    /**
     * @var OffsetVo
     */
    public $offset;

    /**
     * TemplateVo constructor.
     * @param $src
     */
    public function __construct($src)
    {
        parent::__construct($src);
    }

    /**
     * @return OffsetVo
     */
    public function getOffset(): OffsetVo
    {
        return $this->offset;
    }

    /**
     * @param OffsetVo $offset
     */
    public function setOffset(OffsetVo $offset): void
    {
        $this->offset = $offset;
    }



}
