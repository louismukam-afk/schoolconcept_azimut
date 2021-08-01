<?php namespace gesecole;

use Illuminate\Database\Eloquent\Model;

class Vars extends Model {

	 const INSCRIPTION_TYPE = -1;

	 const DECISION_ALLOW = 1;
	 const DECISION_DENY = 2;
	 const DECISION_UNSET = 0;

}
