<?php

namespace pChart\Barcodes;

use pChart\Barcodes\Encoders\QRCode\Encoder;
use pChart\pException;

class QRCode extends pConf {

	private $myPicture;

	function __construct(\pChart\pDraw $myPicture)
	{
		$this->myPicture = $myPicture;
	}

	private function render($encoded)
	{
		$h = count($encoded);

		$image = $this->myPicture->gettheImage();
		$padding = $this->get('padding');
		$scale = $this->get('scale');
		$palette = $this->get('palette');
		$StartX = $this->get('StartX');
		$StartY = $this->get('StartY');

		// Apply scaling & aspect ratio
		$width = ($h * $scale) + $padding * 2;

		// Draw the background
		$bgColorAlloc = $this->myPicture->allocatepColor($palette['bgColor']);
		imagefilledrectangle($image, $StartX, $StartY, $StartX + $width, $StartY + $width, $bgColorAlloc);
		$colorAlloc = $this->myPicture->allocatepColor($palette['color']);

		// Render the barcode
		for($y = 0; $y < $h; $y++) {
			for($x = 0; $x < $h; $x++) {
				if ($encoded[$y][$x] & 1) {
					imagefilledrectangle(
						$image,
						($x * $scale) + $padding + $StartX,
						($y * $scale) + $padding + $StartY,
						(($x + 1) * $scale - 1) + $padding + $StartX,
						(($y + 1) * $scale - 1) + $padding + $StartY,
						$colorAlloc
					);
				}
			}
		}
	}

	public function draw(string $text, array $opts = [])
	{
		$defaults = [
			'scale' => 3,
			'padding' => 4
		];
		$this->apply_user_options($opts, $defaults);

		$level = 0;
		if (isset($opts['level'])){
			switch(strtoupper($opts['level'])){
				case "L":
					#$level = 0;
					break;
				case "M":
					$level = 1;
					break;
				case "Q":
					$level = 2;
					break;
				case "H":
					$level = 3;
					break;
				default:
					throw pException::InvalidInput("Invalid value for level");
			}
		}

		$this->check_range('scale', 0, 20);
		$this->check_range('padding', 0, 20);

		if($text == '\0' || $text == '') {
			throw pException::InvalidInput("Invalid value for text");
		}

		if (isset($opts['hint'])){
			switch(strtolower($opts['hint'])){
				case "numeric":
					$hint = 0;
					break;
				case "alphanumeric":
					$hint = 1;
					break;
				case "byte":
					$hint = 2;
					break;
				case "kanji":
					$hint = 3;
					break;
					default:
						throw pException::InvalidInput("Invalid value for hint");
			}
		} else {
			$hint = -1;
		}

		$encoded = (new Encoder($level))->encodeString($text, $hint);
		$this->render($encoded);
	}
}
