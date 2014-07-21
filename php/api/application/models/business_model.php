<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function first_post() {
		$sql = 
		"
			SELECT
				*
			FROM
				post
			ORDER BY
				post_time ASC
			LIMIT 1
		";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0) {
			$result = $query->result_array();
			return $result[0];
		}
		else
			return NULL;

	}

	/**
	 *
	 * @param int Unix timestamp
	 * @return array(period, result)
	 */
	public function distribution_of_posts_made_by_district($date = "") {
		
		//if the date provided, the period should be from the first date to the end date of the month that the date given
		
		//otherwise, the first date is the date of the first post or Unix timestmp = 0 if no first post
		//the end date is NOW

		$period = $this->period_by_date($date);

		$first_post = $this->first_post();
		if($date == "" && $first_post != NULL)
			$period["begin"] = $first_post["post_time"];

		$sql =
		"
		SELECT
			district.name AS district_name,
			IF(post.total IS NULL, 0, post.total) AS total
		FROM
			district
		LEFT JOIN
		(
			SELECT
				COUNT(*) AS total,
				district_id
			FROM
				post
			WHERE
				post_time BETWEEN ? AND ?
			GROUP BY
				district_id
		) AS post
		ON
			district.district_id = post.district_id
		ORDER BY
			post.total DESC
		";

		$query = $this->db->query($sql, array($period["begin"], $period["end"]));
		return array(
			"period" => $period,
			"result" => $query->result_array()
		);
	}

	public function top_most_popular_tags($date = "", $limit = 10) {

		$period = $this->period_by_date($date);

		$sql = 
		"
		SELECT
			tag.name AS tag_name,
			search.num_of_tags,
			post.tag_rating
		FROM
			tag
		INNER JOIN
		(
			SELECT
				COUNT(*) AS num_of_tags,
				tag_id
			FROM
				user_tag_search
			WHERE
				created_time BETWEEN ? AND ?
			GROUP BY
				tag_id
		) AS search
		ON
			tag.tag_id = search.tag_id
		INNER JOIN
		(
			SELECT
				AVG(post.rating) AS tag_rating,
				post_tag.tag_id
			FROM
				post
			INNER JOIN
				post_tag
			ON
				post.post_id = post_tag.post_id
			INNER JOIN
			(
				SELECT
					tag_id
				FROM
					user_tag_search
				WHERE
					created_time BETWEEN ? AND ?
				GROUP BY
					tag_id
				ORDER BY
					1
				LIMIT ?
			) AS search
			ON
				post_tag.tag_id = search.tag_id
			WHERE
				post_time BETWEEN ? AND ?
			GROUP BY
				tag_id
		) AS post
		ON
			tag.tag_id = post.tag_id
		ORDER BY
			search.num_of_tags DESC
		LIMIT ?
		";

		//$query = $this->db->query($sql, array($period["begin"], $period["end"], $limit));
		return array(
			"period" => $period,
			"result" => $query->result_array(),
			"num_rows" => $query->num_rows()
		);
	}

	private function period_by_date($date = "") {
		//calculate the period
		$begin_date = 0;
		$end_date = time();

		if($date != "") {
			$begin_date = mktime(0, 0, 0, date("n", $date), 1, date("Y", $date));
			$end_date = mktime(0, 0, 0, date("n", $date) + 1, 1, date("Y", $date)) - 86400;
		}

		return array(
			"begin" => date("Y-m-d H:i:s", $begin_date),
			"end" => date("Y-m-d H:i:s", $end_date)
		);

	}

}