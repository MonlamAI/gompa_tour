<?php
/**
 * Class QRGdImageGIFTest
 *
 * @created      11.12.2021
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2021 smiley
 * @license      MIT
 */

namespace chillerlan\QRCodeTest\Output;

use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\{QRGdImageGIF, QROutputInterface};

/**
 *
 */
final class QRGdImageGIFTest extends QRGdImageTestAbstract{

	protected string $type = QROutputInterface::GDIMAGE_GIF;

	protected function getOutputInterface(QROptions $options, QRMatrix $matrix):QROutputInterface{
		return new QRGdImageGIF($options, $matrix);
	}

}
