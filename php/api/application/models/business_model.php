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

		//$first_post = $this->first_post();
		//if($date == "" && $first_post != NULL)
		//	$period["begin"] = $first_post["post_time"];

		$sql =
		"
		SELECT
			district.name AS district_name,
			IF(post.total IS NULL, 0, post.total) AS num_of_post
		FROM
			district
		LEFT JOIN											#must be left join, null will be returned if 0 count
		(													#find num of post by district within a period
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
			"result" => $query->result_array(),
			"num_rows" => $query->num_rows()
		);
	}

	public function top_most_popular_created_tags($date = "", $limit = 10) {
		$period = $this->period_by_date($date);

		$sql = 
		"
		SELECT
			tag.name AS tag_name,
			created.num_of_tags
		FROM
			tag
		INNER JOIN
		(
			SELECT
				COUNT(*) AS num_of_tags,
				tag_id
			FROM
				post
			INNER JOIN
				post_tag
			ON
				post.post_id = post_tag.post_id
			WHERE
				post_time BETWEEN ? AND ?
			GROUP BY
				tag_id
			ORDER BY
				1 DESC
			LIMIT
				?
		) AS created
		ON
			tag.tag_id = created.tag_id
		";

		$query = $this->db->query($sql, array($period["begin"], $period["end"], $limit));

		return array(
			"period" => $period,
			"result" => $query->result_array(),
			"num_rows" => $query->num_rows()
		);
	}

	public function top_most_searched_tags($date = "", $limit = 10) {
		$period = $this->period_by_date($date);

		$sql = 
		"
		SELECT
			tag.name AS tag_name,
			search.num_of_tags
		FROM
			tag
		INNER JOIN											#find number of tags
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
			ORDER BY
				1 DESC
			LIMIT
				?
		) AS search
		ON
			tag.tag_id = search.tag_id
		";

		$query = $this->db->query($sql, array($period["begin"], $period["end"], $limit));

		return array(
			"period" => $period,
			"result" => $query->result_array(),
			"num_rows" => $query->num_rows()
		);
	}

	/*public function top_most_popular_tags($date = "", $limit = 10) {

		$period = $this->period_by_date($date);

		$sql = 
		"
		SELECT
			tag.name AS tag_name,
			search.num_of_tags,
			IF(post_monthly.tag_rating IS NULL, 0, post_monthly.tag_rating) AS tag_rating_monthly,
			IF(post2_monthly.time_spent IS NULL, 0, post2_monthly.time_spent) AS time_spent_monthly
		FROM
			tag
		INNER JOIN											#find number of tags
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
			ORDER BY
				1 DESC
			LIMIT
				?
		) AS search
		ON
			tag.tag_id = search.tag_id
		LEFT JOIN											#find tag rating within a period
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
			INNER JOIN										#limit the number of post
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
				LIMIT
					?
			) AS search
			ON
				post_tag.tag_id = search.tag_id
			WHERE
				post_time BETWEEN ? AND ?
			GROUP BY
				tag_id
		) AS post_monthly
		ON
			tag.tag_id = post.tag_id
		LEFT JOIN											#find time spent for the post within a period
		(
			SELECT
				AVG(user_post_view.duration) AS time_spent,
				post_tag.tag_id
			FROM
				post
			INNER JOIN
				post_tag
			ON
				post.post_id = post_tag.post_id
			INNER JOIN
				user_post_view
			ON
				post.post_id = user_post_view.post_id
			INNER JOIN										#limit the number of post
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
				LIMIT
					?
			) AS search
			ON
				post_tag.tag_id = search.tag_id
			WHERE
				post_time BETWEEN ? AND ?
			GROUP BY
				tag_id
		) AS post2_monthly
		ON
			tag.tag_id = post2.tag_id
		";

		if($date == "")
			$sql .= 
		"
		LEFT JOIN											#find tag rating for all period
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
			INNER JOIN										#limit the number of post
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
				LIMIT
					?
			) AS search
			ON
				post_tag.tag_id = search.tag_id
			GROUP BY
				tag_id
		) AS post_all
		ON
			tag.tag_id = post.tag_id
		LEFT JOIN											#find time spent of post for all period
		(
			SELECT
				AVG(user_post_view.duration) AS time_spent,
				post_tag.tag_id
			FROM
				post
			INNER JOIN
				post_tag
			ON
				post.post_id = post_tag.post_id
			INNER JOIN
				user_post_view
			ON
				post.post_id = user_post_view.post_id
			INNER JOIN										#limit the number of post
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
				LIMIT
					?
			) AS search
			ON
				post_tag.tag_id = search.tag_id
			GROUP BY
				tag_id
		) AS post2_all
		ON
			tag.tag_id = post2.tag_id
		";
		$query = $this->db->query($sql, array($period["begin"], $period["end"], $limit, $period["begin"], $period["end"], $limit, $period["begin"], $period["end"], $period["begin"], $period["end"], $limit, $period["begin"], $period["end"]));

		return array(
			"period" => $period,
			"result" => $query->result_array(),
			"num_rows" => $query->num_rows()
		);
	}*/

	public function company_user($company_id, $date = "") {
		$period = $this->period_by_date($date);


	}

	public function company_no_of_post_made_by_district($company_id, $date = "") {
		$period = $this->period_by_date($date);


		
	}

	public function company_no_of_post_created_by_district($company_id, $date = "") {
		$period = $this->period_by_date($date);


	}

	public function company_ratings($company_id, $date = "") {
		$period = $this->period_by_date($date);

		$sql = 
		"
		SELECT
			rating,
			COUNT(*) AS num_of_rating
		FROM
			company
		INNER JOIN
			post
		ON
			company.company_id = post.company_id
		WHERE
			company.company_id = ? AND
			post.post_time BETWEEN ? AND ?
		GROUP BY
			rating
		ORDER BY
			1 DESC
		";
		$query = $this->db->query($sql, array($company_id, $period["begin"], $period["end"]));
		return array(
			"period" => $period,
			"result" => $query->result_array(),
			"num_rows" => $query->num_rows()
		);
	}

	public function company_tags($company_id, $date = "") {
		$period = $this->period_by_date($date);

		
	}

	private function period_by_date($date = "") {
		//calculate the period
		$begin_date = mktime(0, 0, 0, 5, 1, 2014);
		$end_date = time();

		if($date != "") {
			$begin_date = mktime(0, 0, 0, date("n", $date), 1, date("Y", $date));
			$end_date = mktime(0, 0, 0, date("n", $date) + 1, 1, date("Y", $date)) - 86400;
		}

		return array(
			"begin" => date("Y-m-d H:i:s", $begin_date),
			"begin_timestamp" => $begin_date,
			"end" => date("Y-m-d H:i:s", $end_date),
			"end_timestamp" => $end_date
		);

	}

}