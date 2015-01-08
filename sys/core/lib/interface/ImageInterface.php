<?php

namespace CLib;

interface ImageInterface{
	public function open($imgname);

	public function save($imgname, $type = NULL, $interlace = true);

	public function width();

	public function height();

	public function type();

	public function mime();

	public function size();

	public function crop($w, $h, $x = 0, $y = 0, $width = NULL, $height = NULL);

	public function thumb($width, $height, $type = Image::IMAGE_THUMB_SCALE);

	public function water($source, $locate = Image::IMAGE_WATER_SOUTHEAST, $alpha = 80);

	public function text($text, $font, $size, $color = '#00000000', $locate = Image::IMAGE_WATER_SOUTHEAST, $offset = 0, $angle = 0);

}