<?php

final class oss
{
	static 	private $oss_sdk_service;
	static 	private $bucket;

	static private function _init()
	{
		require_once BASE_DATA_PATH . "/api/oss/sdk.class.php";
		self::$oss_sdk_service = new ALIOSS(NULL, NULL, C("oss.api_url"));
		self::$oss_sdk_service->set_debug_mode(false);
		self::$bucket = C("oss.bucket");
	}

	static private function _format($response)
	{
		echo "|-----------------------Start---------------------------------------------------------------------------------------------------\n";
		echo "|-Status:" . $response->status . "\n";
		echo "|-Body:\n";
		echo $response->body . "\n";
		echo "|-Header:\n";
		print_r($response->header);
		echo "-----------------------End-----------------------------------------------------------------------------------------------------\n\n";
	}

	static public function upload($src_file, $new_file)
	{
		self::_init();

		try {
			$response = self::$oss_sdk_service->upload_file_by_file(self::$bucket, $new_file, $src_file);

			if ($response->status == "200") {
				return true;
			}
			else {
				return false;
			}
		}
		catch (Exception $ex) {
			return false;
		}
	}

	static public function del($img_list)
	{
		self::_init();

		try {
			$options = array("quiet" => false);
			$response = self::$oss_sdk_service->delete_objects(self::$bucket, $img_list, $options);

			if ($response->status == "200") {
				return true;
			}
			else {
				return false;
			}
		}
		catch (Exception $ex) {
			return false;
		}
	}
}

defined("InShopBN") || exit("Access Invalid!");

?>
