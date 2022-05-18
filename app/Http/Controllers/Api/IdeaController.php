<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Http\Requests\{
  IdeaCreateRequest,
  IdeaUpdateRequest,
  IdeaListRequest
};

use App\Models\{
  Idea
};

class IdeaController extends Controller
{
  protected $list_fields      = ['id', 'rownum', 'author_id', 'title', 'created_at', 'updated_at', 'deleted_at'];
  protected $list_relations   = ['author'];
  protected $record_fields    = ['id', 'author_id', 'title', 'created_at', 'updated_at', 'deleted_at'];
  protected $record_relations = ['author'];

  /**
   * List
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Http\Requests\IdeaListRequest  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function list(Request $request)
  {
    $current_page = ($request->exists("page")) ? (int) $request->query("page") : 1;
    $current_page = ($current_page > 0) ? $current_page : 1;
    $pagination   = Idea::getPaginator($current_page);
    $offset       = $pagination->per_page * ($pagination->current_page - 1);

    $idea_list = Idea::query()
                     ->withTrashed()
                     ->with($this->list_relations)
                     ->where('rownum', '>', $offset)
                     ->limit($pagination->per_page)
                     ->orderBy('rownum')
                     ->get($this->list_fields);
    return response()->json(['pagination' => $pagination, 'data' => $idea_list,], Response::HTTP_OK);
  }

  /**
   * Get idea
   *
   * @param int $idea_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function get(Request $request, int $idea_id)
  {
    $idea = Idea::query()
                ->with($this->record_relations)
                ->select($this->record_fields)
                ->find($idea_id);
    if (!$idea ) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }
    return response()->json($idea, Response::HTTP_OK);
  }

  /**
   * Create idea
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function create(IdeaCreateRequest $request)
  {
    $max_rownum = Idea::query()->max('rownum');
    $input      = $request->only(['title']);
    $input["rownum"]     = $max_rownum + 1;
    $input['author_id']  = rand(1, 6);
    $input['created_at'] = now();

    $idea = Idea::create($input);
    return response()->json($idea, Response::HTTP_CREATED);
  }

  /**
   * Update idea
   *
   * @param int $idea_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(IdeaUpdateRequest $request, int $idea_id)
  {
    $input = $request->only(['title']);
    $idea = Idea::find($idea_id);
    if (!$idea) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }
    $idea->update($input);
    $idea->load($this->record_relations);
    return response()->json($idea, Response::HTTP_OK);
  }

  /**
   * Delete idea
   *
   * @param int $idea_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function delete(Request $request, int $idea_id)
  {
    $idea = Idea::find($idea_id);
    if (!$idea) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }

    $idea->load($this->record_relations);

    $idea->delete();
    // $max_rownum = Idea::query()->max('rownum');
    // DB::table('idea')
    //   ->where('id', '>', $idea->id)
    //   ->update(['rownum' => DB::raw('rownum - 1')]);
    // update idea set rownum = rownum - 1 where id > id_of_deleted_row
    return response()->json($idea, Response::HTTP_OK);
  }


  /**
   * Delete idea
   *
   * @param int $idea_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function restore(Request $request, int $idea_id)
  {
    $idea = Idea::onlyTrashed()->find($idea_id);
    if (!$idea) {
      return response()->json([], Response::HTTP_NOT_FOUND);
    }

    $idea->load($this->record_relations);
    $idea->restore();
    return response()->json($idea, Response::HTTP_OK);
  }
}
