<?php


use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\WebDriverBy;
use WPooWTests\WPooWBaseTestCase;

include __DIR__.'/../wpAPI.php';

 class CustomPostTypeTest extends WPooWBaseTestCase
 {
     private static $samplePostType1 = [
         'id' => '_wpoow_test_menu',
         'title' => 'WPooW Test Menu',
         'fields' => [
             [
                 'id' => '_test_text_field',
                 'label' => 'Sample Text Field',
                 'test_value' => 'Sample Text'
             ]
         ]
     ];

     /**
      * @WP_BeforeRun initializesWithOutAnyErrorsWPBeforeRun
      */
     public function testInitializesWithOutAnyErrors()
     {
         $this->loginToWPAdmin();
         $this->assertFalse(strpos($this->driver->getPageSource(), 'Stack trace'));
     }


     /**
      * @WP_BeforeRun createPostTypeWPBeforeRun
      */
     public function testCanCreatePostType()
     {
         $canNavigate = false;

         $this->loginToWPAdmin();
         $this->navigateToMenuItems(self::$samplePostType1['id']);

         if (strpos($this->driver->getCurrentURL(), sprintf("edit.php?post_type=%s", self::$samplePostType1['id'])) !== false) {
             $canNavigate = true;
         }

         $this->assertTrue($canNavigate);
     }


     /**
      * @WP_BeforeRun createPostTypeWPBeforeRun
      */
     public function testCanPublishPostType()
     {
         $this->loginToWPAdmin();
         $this->assertTrue($this->addPost(self::$samplePostType1['id']) != null);
     }

     /**
      * @WP_BeforeRun addFieldWPBeforeRun
      */
     public function testCanAddField()
     {
         $this->loginToWPAdmin();
         $this->navigateToMenuItems(self::$samplePostType1['id']);
         $FieldInPostTypeGrid= $this->hasFieldInPostTypeGrid(self::$samplePostType1['id'], self::$samplePostType1['fields'][0]);
         $FieldInPostTypeAddForm = $this->hasFieldInPostTypeAddForm(self::$samplePostType1['id'], self::$samplePostType1['fields'][0]);
         $this->assertTrue($FieldInPostTypeGrid && $FieldInPostTypeAddForm);
     }

     /**
      * @WP_BeforeRun addFieldWPBeforeRun
      */
     public function testCanEditPostType()
     {
         $this->loginToWPAdmin();
         $postID = $this->addPost(self::$samplePostType1['id'], self::$samplePostType1['fields']);
         $this->assertTrue($this->editPost(self::$samplePostType1['id'], $postID, self::$samplePostType1['fields']));

         $this->expectException(NoSuchElementException::class);
         $this->driver->findElement(WebDriverBy::xpath("//tr[@id='${postID}']"));
     }


     /**
      * @WP_BeforeRun addFieldWPBeforeRun
      */
     public function testCanDeletePostType()
     {
         $this->loginToWPAdmin();
         $postID = $this->addPost(self::$samplePostType1['id'], self::$samplePostType1['fields']);
         $this->assertTrue($this->deletePost(self::$samplePostType1['id'], $postID, self::$samplePostType1['fields']));
     }


     // WP_BEFORE RUN FUNCTIONS //
     
     public static function initializesWithOutAnyErrorsWPBeforeRun()
     {
         new wpAPI();
     }

     public static function createPostTypeWPBeforeRun()
     {
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
         $wpOOWTestPage->render();
     }

     public static function addFieldWPBeforeRun()
     {
         $wpOOW = new wpAPI();
         $wpOOWTestPage = $wpOOW->CreatePostType(self::$samplePostType1['id'], self::$samplePostType1['title'], true);
         $wpOOWTestPage->AddField(new Text(self::$samplePostType1['fields'][0]['id'], self::$samplePostType1['fields'][0]['label']));
         $wpOOWTestPage->render();
     }
 }
