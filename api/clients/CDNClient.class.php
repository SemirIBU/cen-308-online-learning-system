<?php
require_once dirname(__FILE__) . '/../config.php';
require_once dirname(__FILE__) . '/../../vendor/autoload.php';

use Aws\S3\S3Client;

class CDNClient
{

  private $client;

  public function __construct()
  {
    $this->client = new Aws\S3\S3Client([
      'version' => 'latest',
      'region'  => Config::CDN_REGION(),
      'endpoint' => Config::CDN_BASE_URL(),
      'credentials' => [
        'key'    => Config::CDN_KEY(),
        'secret' => Config::CDN_SECRET()
      ]
    ]);
  }

  /**
   * Upload file to CDN and return the public URL back
   * @param $filename - name of the file on CDN
   * @param $content - base64 encoded file content
   */
  public function upload($filename, $content)
  {
    $response = $this->client->putObject([
      'Bucket' => Config::CDN_SPACE(),
      'Key'    => $filename,
      'Body'   => base64_decode($content),
      'ACL'    => 'public-read'
    ]);
    return $response->get("ObjectURL");
  }

  public function get_url($filename)
  {
    $url = $this->client->getObjectUrl(Config::CDN_SPACE(), $filename);
    return $url;
  }
  public function if_file_exists($filename)
  {
    $info = $this->client->doesObjectExist(Config::CDN_SPACE(), $filename);
    if ($info) {
      return true;
    } else {
      return false;
    }
  }
  public function delete($filename)
  {
    $result = $this->client->deleteObject([
      'Bucket' => Config::CDN_SPACE(),
      'Key'    => $filename
    ]);
    return $result;
  }

  public function checkRemoteFile($url)
  {
    $headers = @get_headers($url);
    if ($headers && strpos($headers[0], '403')) {
      return false;
    } else {
      return true;
    }
  }
}
