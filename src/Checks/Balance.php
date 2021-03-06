<?php
namespace TestSuite\Checks;

use \TestSuite\Settings;

class Balance extends AbstractCheck
{
  protected function request()
  {
    $message = $this->getPreparedMessage();
    $message->setMTI('0200');
    $message->setField(2, explode("=", Settings::VALID_TRACK2)[0]);
    $message->setField(3, Settings::PROCESSING_CODE_BALANCE);
    $message->setField(35, Settings::VALID_TRACK2);
    $message->setField(52, hex2bin(Settings::VALID_PINBLOCK));
    $message->setField(53, Settings::PINBLOCK_PARAMS);

    return $message;
  }

	protected function expectation($response)
	{
		$balanceString = $response->getField(54);

		$currency = (int)substr($balanceString, 4, 3);
		$balance = (int)substr($balanceString, 8);

		if ($currency !== 643) {
			throw new \Exception('Balance currency missmatch, expected: 643 got: ' . $currecy);
		}

		if ($balance < 0) {
			throw new \Exception('Balance is low, expected posivite value, got negative');
		}

		return true;
	}
}
