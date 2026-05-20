<?php namespace gesecole\Services;

use Auth;
use gesecole\bourse;
use gesecole\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Log;

class BourseService {
  /**
   * Create a new scholarship entry
   * @param \gesecole\bourse $bourse
   * @return \gesecole\bourse
   */
  public static function create(bourse $bourse) {
    $user = Auth::user();
      $logData=$bourse->toArray();
    if ($user) {
      $logData['userId'] = $user->id;
    }

    Log::debug('Creating new bourse', $logData);
    $bourse->save();
    return $bourse;
  }

  /**
   * Update a scholarship entry
   * @param \gesecole\bourse $bourse
   * @return \gesecole\bourse
   */
  public static function update(bourse $bourse) {
    $user = Auth::user();
    $data = $bourse->toArray();
    $logData = $data;

    if ($user) {
      $logData['userId'] = $user->id;
    }

    Log::debug('Updating bourse', $logData);
    $oldBourse = bourse::find($bourse->id);
    if (!$oldBourse) {
      Log::error("Failed to update bourse: id not found");
      throw new NotFoundException(trans('admin.scholarship_not_exists'));
    }
    unset($data['id']);
    $oldBourse->update($bourse->toArray());
    return $bourse;
  }

  /**
   * Get a scholarship entry
   * @param int $id
   * @return \gesecole\bourse
   */
  public static function get($id) {
    Log::debug('Fetching bourse', ['id' => $id]);
    if (!$id) {
      Log::error("Cannot get bourse: Wrong id");
      return null;
    }

    return bourse::find($id);
  }

  /**
   * Delete a scholarship entry
   * @param array|int $ids
   */
  public static function delete($ids) {
    $user = Auth::user();
    $logData = [$ids];

    if ($user) {
      $logData['userId'] = $user->id;
    }

    Log::debug('Deleting bourse', $logData);
    $ids = is_array($ids) ? $ids : func_get_args();
    bourse::destroy($ids);
  }
}