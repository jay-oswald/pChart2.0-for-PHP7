<?php 
/*
pColor - Data structure for colors

Version     : 2.4.0-dev
Made by     : Momchil Bozhinov
Last Update : 01/09/2019

*/

namespace pChart;

class pColor
{
	public $R;
	public $G;
	public $B;
	public $Alpha;

	/* Floats are required for pGradient */
	public function __construct(float $R = 0, float $G = 0, float $B = 0, float $Alpha = 100)
	{
		($R < 0)	AND $R = 0;
		($R > 255)	AND $R = 255;

		switch (func_num_args()){
			case 1:
			case 2:
				$G = $R;
				$B = $R;
				$Alpha = 100;
				break;
			case 3:
			case 4:
				($G < 0) 	AND $G = 0;
				($G > 255)	AND $G = 255;
				($B < 0) 	AND $B = 0;
				($B > 255)	AND $B = 255;
				($Alpha < 0)	AND $Alpha = 0;
				($Alpha > 100)	AND $Alpha = 100;
				break;
			case 0: # random
				$R = rand(0, 255);
				$G = rand(0, 255);
				$B = rand(0, 255);
		}

		$this->R = $R;
		$this->G = $G;
		$this->B = $B;
		$this->Alpha = $Alpha;
	}

	public function toHex()
	{
		$R = dechex(intval($this->R));
		$G = dechex(intval($this->G));
		$B = dechex(intval($this->B));

		return  "#".(strlen($R) < 2 ? '0' : '').$R.(strlen($G) < 2 ? '0' : '').$G.(strlen($B) < 2 ? '0' : '').$B;
	}

	public function RGBChange(float $howmuch)
	{
		$this->R += $howmuch;
		$this->G += $howmuch;
		$this->B += $howmuch;

		($this->R < 0) AND $this->R = 0;
		($this->G < 0) AND $this->G = 0;
		($this->B < 0) AND $this->B = 0;
		($this->R > 255) AND $this->R = 255;
		($this->G > 255) AND $this->G = 255;
		($this->B > 255) AND $this->B = 255;

		return $this;
	}

	public function AlphaSet(float $howmuch)
	{
		$this->Alpha = $howmuch;

		($this->Alpha < 0)   AND $this->Alpha = 0;
		($this->Alpha > 100) AND $this->Alpha = 100;

		return $this;
	}

	public function AlphaChange(float $howmuch)
	{
		$this->Alpha += $howmuch;

		($this->Alpha < 0)   AND $this->Alpha = 0;
		($this->Alpha > 100) AND $this->Alpha = 100;

		return $this;
	}

	public function AlphaSlash(float $howmuch)
	{
		$this->Alpha = $this->Alpha / $howmuch;

		return $this;
	}

	public function AlphaMultiply(float $howmuch)
	{
		$this->Alpha = $this->Alpha * $howmuch;

		($this->Alpha < 0)   AND $this->Alpha = 0;
		($this->Alpha > 100) AND $this->Alpha = 100;

		return $this;
	}

	public function get()
	{
		return [$this->R, $this->G, $this->B, $this->Alpha];
	}

	public function newOne()
	{
		return (clone $this);
	}

}

?>