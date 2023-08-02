<?php

namespace JunaidiAR\ImageKitAdapter\Tests;

use ImageKit\ImageKit;
use PHPUnit\Framework\TestCase;
use JunaidiAR\ImageKitAdapter\ImageKitAdapter;

class ImageKitAdapterTest extends TestCase
{
  protected $client;
  protected $adapter;

  public function setUp(): void
  {
    parent::setUp();

    $this->client = new ImageKit(
      "your_public_key",
      "your_private_key",
      "https://ik.imagekit.io/demo/"
    );

    $this->adapter = new ImageKitAdapter($this->client);
  }

  public function testGetClient()
  {
    $this->assertInstanceOf(ImageKit::class, $this->adapter->getClient());
  }

  /** @test */
  public function test_can_write()
  {
    $contents = 'https://digitaliz.net/images/object-1.png';
    $this->adapter->upload('testing/object-1.png', $contents);
    $this->assertTrue($this->adapter->fileExists('testing/object-1.png'));
  }
}
