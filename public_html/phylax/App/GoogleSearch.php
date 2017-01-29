<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

trait GoogleSearch {

    public $list = array();
    public $client = null;

    public function try_videoType( $item ) {
        $r = null;
        switch( $item ) {
            case 'youtube#video':    $r = 'V'; break;
            case 'youtube#channel':  $r = 'C'; break;
            case 'youtube#playlist': $r = 'P'; break;
        }
        return $r;
    }

    public function translate_youtube_video( $id, $snippet, $typ ) {
        if (
            ( $id != '') &&
            isset( $snippet['channelId'] ) &&
            isset( $snippet['channelTitle'] ) &&
            isset( $snippet['publishedAt'] ) &&
            isset( $snippet['title'] ) &&
            isset( $snippet['description'] ) &&
            isset( $snippet['thumbnails']['default']['url'] )
        ) {
            return array(
                         'type' => $typ,
                           'id' => $id,
                          'url' => 'https://www.youtube.com/watch?v=' . $id,
                        'title' => $snippet['title'],
                         'date' => $snippet['publishedAt'],
                     'abstract' => $snippet['description'],
                'thumbnail_url' => $snippet['thumbnails']['default']['url'],
                   'channel_id' => $snippet['channelId'],
                'channel_title' => $snippet['channelTitle'],
            );
        }
        return null;
    }

    public function translate_youtube_channel( $item, $typ ) {
        if (
            isset( $item['publishedAt'] ) &&
            isset( $item['channelId'] ) &&
            isset( $item['title'] ) &&
            isset( $item['description'] ) &&
            isset( $item['thumbnails']['default']['url'] )
        ) {
            return array(
                         'type' => $typ,
                          'url' => 'https://www.youtube.com/channel/' . $item['channelId'],
                   'channel_id' => $item['channelId'],
                        'title' => $item['title'],
                     'abstract' => $item['description'],
                         'date' => $item['publishedAt'],
                'thumbnail_url' => $item['thumbnails']['default']['url'],
            );
        }
        return null;
    }

    public function translate_youtube_playlist( $id, $item, $typ ) {
        if (
            ( $id != '' ) &&
            isset( $item['channelId'] ) &&
            isset( $item['channelTitle'] ) &&
            isset( $item['description'] ) &&
            isset( $item['publishedAt'] ) &&
            isset( $item['title'] ) &&
            isset( $item['thumbnails']['default']['url'] )
        ) {
            return array(
                         'type' => $typ,
                          'url' => 'https://www.youtube.com/playlist?list=' . $id,
                  'playlist_id' => $id,
                        'title' => $item['title'],
                     'abstract' => $item['description'],
                         'date' => $item['publishedAt'],
                   'channel_id' => $item['channelId'],
                'channel_title' => $item['channelTitle'],
                'thumbnail_url' => $item['thumbnails']['default']['url'],
            );
        }
        return null;
    }

    public function query_youtube( $term ) {
        $this->client = new \Google_Client();
        $this->client->setDeveloperKey( GOOGLE_DEVELOPER_KEY );
        $this->youtube = new \Google_Service_YouTube( $this->client );
        $this->list = array();
        try {
            $r = $this->youtube->search->listSearch( 'id,snippet', array(
                'q' => $term,
                'maxResults' => opt('max_results_video'),
            ) );
            if (
                is_object( $r ) &&
                isset( $r['modelData'] ) &&
                is_array( $r['modelData'] ) &&
                isset( $r['modelData']['pageInfo'] ) &&
                is_array( $r['modelData']['pageInfo'] ) &&
                isset( $r['items'] ) &&
                is_array( $r['items'] ) &&
                ( count( $r['items'] ) > 0 ) &&
                isset( $r['modelData']['pageInfo']['totalResults'] ) &&
                is_numeric( $r['modelData']['pageInfo']['totalResults'] ) &&
                ( $r['modelData']['pageInfo']['totalResults'] > 0 )
            ) {
                foreach( $r['items'] as $item ) {
                    if ( isset( $item['id']['kind'] ) && ( $item['id']['kind'] != '' ) ) {
                        $typ = $this->try_videoType( $item['id']['kind'] );
                        $ret = '';
                        switch( $typ ) {
                            case 'V': $ret = $this->translate_youtube_video( $item['id']['videoId'], $item['snippet'], $typ ); break;
                            case 'C': $ret = $this->translate_youtube_channel( $item['snippet'], $typ ); break;
                            case 'P': $ret = $this->translate_youtube_playlist( $item['id']['playlistId'], $item['snippet'], $typ ); break;
                        }
                        if ( is_array( $ret ) && ( count( $ret ) > 0 ) ) {
                            $this->list[] = $ret;
                        }
                    }
                }
            }
        } catch ( Google_ServiceException $e ) {
            # błędy Google Service, nie opisane, w razie czego - nic nie znaleźliśmy
        } catch ( Google_Exception $e ) {
            # błędy Google, nie opisane, w razie czego - nic nie znaleźliśmy
        }
    }

    public function query_google( $service, $term ) {
        switch( $service ) {
            case 'youtube':
                $this->query_youtube( $term );
                break;
        }
    }

    public function set_constants() {
        define( __NAMESPACE__ . '\GOOGLE_DEVELOPER_KEY', 'AIzaSyDFPM9aFWYRW36Wh8-H2bwPce9sWELPFCE' );
    }

}

# EOF