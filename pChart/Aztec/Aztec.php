<?php

namespace pChart\Aztec;

use pChart\Aztec\Encoder\Encoder;
use pChart\pConf;

class Aztec extends pConf
{
	private $myPicture;

	public function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	private function render($pixelGrid)
	{
		$image = $this->myPicture->gettheImage();
		$width = count($pixelGrid);
		$ratio = $this->get('ratio');
		$padding = $this->get('padding');
		#$this->size = ($width * $ratio) + ($padding * 2);

		// Extract options
		$bgColorAlloc = $this->myPicture->allocatepColor($this->get('bgColor'));
		imagefill($image, 0, 0, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($this->get('color'));

		// Render the code
		for ($x = 0; $x < $width; $x++) {
			for ($y = 0; $y < $width; $y++) {
				if (isset($pixelGrid[$x][$y])){
					imagefilledrectangle(
						$image, ($x * $ratio) + $padding,
						($y * $ratio) + $padding,
						(($x + 1) * $ratio - 1) + $padding,
						(($y + 1) * $ratio - 1) + $padding,
						$colorAlloc
					);
				}
			}
		}
	}

	public function draw($data, array $opts = [])
	{
		$this->apply_user_options($opts);

		$hint = $this->return_if_match_or_default('hint', ["binary", "dynamic"], 'dynamic');
		$eccPercent = $this->return_if_within_range_or_default('eccPercent', 1, 100, 33);
		$this->set_if_within_range_or_default('ratio', 1, 10, 4);
		$this->set_if_within_range_or_default('padding', 0, 50, 20);

		$pixelGrid = (new Encoder())->encode($data, $eccPercent, $hint);

		$this->render($pixelGrid);
	}
}