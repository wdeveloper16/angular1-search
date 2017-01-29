<?php
/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *
 */
namespace Phylax\SourceMoz;

class Options {

	public function interprete($s) {
		global $config;

		return str_replace([
			'%home_url%',
			'%current_year%',
		], [
			$config['URI']['Home'],
			date('Y'),
		], $s);
	}

	public function create_items(&$d) {
		$d['first_social_urls_str']   = implode(',', array_map('trim', explode("\n", $d['first_social_urls'])));
		$d['social_urls_str']         = implode(',', array_map('trim', explode("\n", $d['social_urls'])));
		$d['exclude_social_urls_str'] = array_map('trim', explode("\n", $d['exclude_social_urls']));
		if (count($d['exclude_social_urls_str']) > 0) {
			foreach ($d['exclude_social_urls_str'] as $key => $item) {
				$d['exclude_social_urls_str'][$key] = '-' . $item . '';
			}
			$d['exclude_social_urls_str'] = implode(',', $d['exclude_social_urls_str']);
		}
	}

	public function __construct(Database $db) {
		if (!isset($_SESSION['Options'])) {
			$o = $db->assoc_read_table(
				'options',
				['opt_slug', 'opt_value']
			);
			$d = [];
			foreach ($o as $item) {
				$d[$item['opt_slug']] = $this->interprete($item['opt_value']);
			}
			$this->create_items($d);
			$_SESSION['Options'] = $d;
		}
	}

}

# EOF