<?php
/**
 * TalkPiece  开源社区
 *  验证码
 * @author     talkpiece <service@talkpiece.com>
 * @copyright  2014  talkpiece
 * @license    http://www.talkpiece.com/license
 * @version    1.0
 * @link       http://www.talkpiece.com
 */

class Captcha {

	private  $capStr = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
	public   $width  = 110 ;
	public   $height = 40;
	public   $capLen = 4 ;

	public  function entry() {

		$this->font = 'Public/css/font/VeraSansBold.ttf';

		$this->img = imagecreatetruecolor( $this->width, $this->height );
		$color = imagecolorallocate( $this->img, 243, 251, 254 );
		imagefilledrectangle( $this->img, 0, $this->height, $this->width, 0, $color );

		$this->writeLine();
		$this->writeNoise();
		$this->writeFont();

		session_start();
		$_SESSION['code'] = strtolower($this->code);

		header( 'Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );
		header( "content-type: image/png" );

		imagepng( $this->img );
		imagedestroy( $this->img );
	}

	protected function writeFont() {
		$capStrLen = strlen( $this->capStr )-1;
		for ( $i=0;$i<$this->capLen;$i++ ) {
			$this->code .= $this->capStr[mt_rand( 0, $capStrLen )];
		}
		$_x = $this->width / $this->capLen;
		for ( $i=0;$i<$this->capLen;$i++ ) {
			$this->fontcolor = imagecolorallocate( $this->img, mt_rand( 0, 156 ), mt_rand( 0, 156 ), mt_rand( 0, 156 ) );
			imagettftext( $this->img, 18, mt_rand( -30, 30 ), $_x*$i+mt_rand( 1, 5 ), $this->height / 1.4, $this->fontcolor, $this->font, $this->code[$i] );
		}
	}

	protected function writeLine() {

		for ( $i = 0; $i < 6; $i++ ) {
			$color = imagecolorallocate( $this->img, mt_rand( 0, 156 ), mt_rand( 0, 156 ), mt_rand( 0, 156 ) );
			imageline( $this->img, mt_rand( 0, $this->width ), mt_rand( 0, $this->height ), mt_rand( 0, $this->width ), mt_rand( 0, $this->height ), $color );
		}

	}

	protected  function writeNoise() {

		for ( $i = 0; $i < 10; $i++ ) {
			$noiseColor = imagecolorallocate( $this->img, mt_rand( 150, 225 ), mt_rand( 150, 225 ), mt_rand( 150, 225 ) );
			imagestring( $this->img, 5, mt_rand( -10, $this->width ),  mt_rand( -10, $this->height ), $this->capStr[mt_rand( 0, 27 )], $noiseColor );
		}

	}

}
?>
