<?php
/**
 * Class ThumbnailPostMerger
 *
 * This class is designed to merge thumbnail images with corresponding posts in WordPress.
 */
class ThumbnailPostMerger {

    // Properties to store search string, post types, and merged results
    private $thumbnailSearchString;
    private $thumbnailPostType;
    private $targetPostType;
    private $thumbnails;
    private $posts;

    /**
     * ThumbnailPostMerger constructor.
     *
     * @param string $thumbnailSearchString - The search string used to query thumbnail images.
     * @param string $thumbnailPostType - The post type of the thumbnail images.
     * @param string $targetPostType - The target post type to merge thumbnails with.
     */
    public function __construct($thumbnailSearchString, $thumbnailPostType, $targetPostType) {
        $this->thumbnailSearchString = $thumbnailSearchString;
        $this->thumbnailPostType = $thumbnailPostType;
        $this->targetPostType = $targetPostType;

        // Initialize thumbnails and posts
        $this->thumbnails = $this->getImages();
        $this->posts = $this->getPosts();
    }

    /**
     * Get thumbnail images based on the provided search string.
     *
     * @return array - Array of thumbnail images with their IDs and titles.
     */
    private function getImages() {
        $images = get_posts(array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
            's' => $this->thumbnailSearchString,
        ));

        // Map to IDs and post titles
        return array_map(function ($image) {
            // Remove search string from post title
            $title = str_replace('-' . $this->thumbnailSearchString, '', $image->post_title);
            return array(
                'thumbnail_id' => $image->ID,
                'image_title' => $title,
            );
        }, $images);
    }

    /**
     * Get posts of the specified post type.
     *
     * @return array - Array of posts with their IDs and titles.
     */
    private function getPosts() {
        $posts = get_posts(array(
            'post_type' => $this->targetPostType,
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        ));

        // Map to IDs and post titles
        return array_map(function ($post) {
            return array(
                'post_id' => $post->ID,
                'title' => $post->post_title,
            );
        }, $posts);
    }

    /**
     * Merge thumbnail images with corresponding posts.
     *
     * @return array - Merged array containing posts with attached thumbnail details.
     */
    private function mergeThumbnailsToPosts() {
        $mergedArray = array();
        $notFoundThumbnails = array();

        foreach ($this->thumbnails as $thumbnail) {
            $thumbnailTitle = $thumbnail['image_title'];
            $found = false;

            foreach ($this->posts as $post) {
                $postTitle = $post['title'];

                if (stripos($postTitle, $thumbnailTitle) !== false) {
                    $mergedArray[] = array_merge($post, $thumbnail);
                    $found = true;
                    break;
                } else {
                    $thumbnailWords = explode(' ', $thumbnailTitle);
                    $found = true;
                    foreach ($thumbnailWords as $word) {
                        if (stripos($postTitle, $word) === false) {
                            $found = false;
                            break;
                        }
                    }

                    if ($found) {
                        $mergedArray[] = array_merge($post, $thumbnail);
                        break;
                    }
                }
            }

            if (!$found) {
                $notFoundThumbnails[] = $thumbnail;
            }
        }

        return array(
            'mergedArray' => $mergedArray,
            'notFoundThumbnails' => $notFoundThumbnails,
        );
    }

    /**
     * Attach merged thumbnails to their respective posts.
     */
    public function attachThumbnailsToPosts() {
        $mergedArray = $this->mergeThumbnailsToPosts();

        foreach ($mergedArray['mergedArray'] as $post) {
            $thumbnailId = $post['thumbnail_id'];
            $postId = $post['post_id'];
            set_post_thumbnail($postId, $thumbnailId);
        }

        echo "Thumbnails attached to posts successfully.";
    }
}

// Example usage:
$thumbnailPostMerger = new ThumbnailPostMerger('current-class-member-thumbnail', 'attachment', 'current-class-member');
$thumbnailPostMerger->attachThumbnailsToPosts();