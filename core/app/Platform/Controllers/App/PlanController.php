<?php namespace Platform\Controllers\App;

use \Platform\Controllers\Core;
use Illuminate\Http\Request;

class PlanController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Plan Controller
   |--------------------------------------------------------------------------
   |
   | Plan related logic
   |--------------------------------------------------------------------------
   */

  /**
   * Plan management
   */
  public function showPlans()
  {
    return view('platform.admin.plans.plans');
  }

  /**
   * New plan
   */
  public function showNewPlan()
  {
    return view('platform.admin.plans.plan-new');
  }

  /**
   * Edit plan
   */
  public function showEditPlan()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where('id', $qs['plan_id'])->first();

      return view('platform.admin.plans.plan-edit', compact('sl', 'plan'));
    }
  }

  /**
   * Add new plan
   */
  public function postNewPlan()
  {
    $input = array(
      'name' => request()->input('name'),
      'price1' => request()->input('price1'),
      'price1_string' => request()->input('price1_string'),
      'price1_period_string' => request()->input('price1_period_string'),
      'price1_subtitle' => request()->input('price1_subtitle'),
      'order_url' => request()->input('order_url'),
      'upgrade_url' => request()->input('upgrade_url'),
      'active' => (bool) request()->input('active', false),
      'limitations' => request()->input('limitations', [])
    );

    $rules = array(
      'name' => 'required',
      'price1' => 'required|numeric',
      'price1_string' => 'required',
      'order_url' => 'url',
      'upgrade_url' => 'url'
    );

    $validator = \Validator::make($input, $rules);

    if($validator->fails())
    {
      $response = array(
        'type' => 'error', 
        'reset' => false, 
        'msg' => $validator->messages()->first()
      );
    }
    else
    {
      $reseller = Core\Reseller::get();

      // Get max order
      $order = \DB::table('plans')->where('reseller_id', $reseller->id)->max('order');
      $order = ($order == null) ? 1 : $order + 1;

      $plan = new \App\Plan;

      $plan->order = $order;
      $plan->reseller_id = $reseller->id;
      $plan->name = $input['name'];
      $plan->price1 = $input['price1'];
      $plan->price1_string = $input['price1_string'];
      $plan->price1_period_string = $input['price1_period_string'];
      $plan->price1_subtitle = $input['price1_subtitle'];
      $plan->order_url = $input['order_url'];
      $plan->upgrade_url = $input['upgrade_url'];
      $plan->limitations = $input['limitations'];
      $plan->active = $input['active'];

      if($plan->save())
      {
        $response = array(
          'type' => 'success',
          'redir' => '#/admin/plans'
        );
      }
      else
      {
        $response = array(
          'type' => 'error',
          'reset' => false, 
          'msg' => $plan->errors()->first()
        );
      }
    }
    return response()->json($response);
  }

  /**
   * Save plan changes
   */
  public function postPlan()
  {
    $sl = request()->input('sl', '');

    if($sl != '')
    {
      $qs = Core\Secure::string2array($sl);
      $plan = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->find($qs['plan_id']);

      $input = array(
        'name' => request()->input('name'),
        'price1' => request()->input('price1'),
        'price1_string' => request()->input('price1_string'),
        'price1_period_string' => request()->input('price1_period_string'),
        'price1_subtitle' => request()->input('price1_subtitle'),
        'order_url' => request()->input('order_url'),
        'upgrade_url' => request()->input('upgrade_url'),
        'active' => (bool) request()->input('active', false),
        'limitations' => request()->input('limitations', [])
      );

      $rules = array(
        'name' => 'required',
        'price1' => 'required|numeric',
        'price1_string' => 'required',
        'order_url' => 'url',
        'upgrade_url' => 'url'
      );

      $validator = \Validator::make($input, $rules);

      if($validator->fails())
      {
        $response = array(
          'type' => 'error', 
          'reset' => false, 
          'msg' => $validator->messages()->first()
        );
      }
      else
      {
        $plan->name = $input['name'];
        $plan->price1 = $input['price1'];
        $plan->price1_string = $input['price1_string'];
        $plan->price1_period_string = $input['price1_period_string'];
        $plan->price1_subtitle = $input['price1_subtitle'];
        $plan->order_url = $input['order_url'];
        $plan->upgrade_url = $input['upgrade_url'];

        if ($qs['plan_id'] != 1) {
          $plan->limitations = $input['limitations'];
          $plan->active = $input['active'];
        }

        if($plan->save())
        {
          $response = array(
            'redir' => '#/admin/plans'
          );
        }
        else
        {
          $response = array(
            'type' => 'error',
            'reset' => false, 
            'msg' => $plan->errors()->first()
          );
        }
      }
      return response()->json($response);
    }
  }

  /**
   * Delete plan
   */
  public function postPlanDelete()
  {
    $sl = request()->input('sl', '');

    if($sl != '') {
      $qs = Core\Secure::string2array($sl);
      $response = array('result' => 'success');

      if($qs['plan_id'] != 1) {
        $plan = \App\Plan::where('id', '=',  $qs['plan_id'])->where('reseller_id', Core\Reseller::get()->id)->forceDelete();
      }
    }
    return response()->json($response);
  }

  /**
   * Re-order plans
   */
  public function postPlanOrder()
  {
    $rows = request()->input('rows', '');

    if($rows != '') {
      foreach($rows as $sl => $order) {
        $qs = Core\Secure::string2array($sl);
        $plan = \App\Plan::where('id', '=',  $qs['plan_id'])->where('reseller_id', Core\Reseller::get()->id)->update(['order' => $order]);
      }
    }
    return response()->json(['result' => 'success']);
  }

  /**
   * Get plan data
   */
  public function getPlanData(Request $request)
  {
    $order_by = $request->input('order.0.column', 0);
    $order = $request->input('order.0.dir', 'asc');
    $search = $request->input('search.regex', '');
    $q = $request->input('search.value', '');
    $start = $request->input('start', 0);
    $draw = $request->input('draw', 1);
    $length = $request->input('length', 10);
    $data = array();

    $aColumn = array('order', 'name', 'price1', 'domain', 'created_at', 'active');

    if($q != '')
    {
      $count = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->where(function ($query) use($q) {
          $query->orWhere('name', 'like', '%' . $q . '%');
        })
        ->count();

      $oData = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->orderBy($aColumn[$order_by], $order)
        ->where(function ($query) use($q) {
          $query->orWhere('name', 'like', '%' . $q . '%');
        })
        ->take($length)->skip($start)->get();
    }
    else
    {
      $count = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->count();
      $oData = \App\Plan::where('reseller_id', Core\Reseller::get()->id)->orderBy($aColumn[$order_by], $order)->take($length)->skip($start)->get();
    }

    if($length == -1) $length = $count;

    $recordsTotal = $count;
    $recordsFiltered = $count;

    foreach($oData as $row) {
      // Make undeletable if plan has users
      $undeletable = ($row->id ==1 ) ? 1 : 0;

      $data[] = array(
        'DT_RowId' => 'row_' . $row->id,
        'order' => $row->order,
        'name' => $row->name,
        'price1_string' => $row->price1_string,
        'active' => $row->active,
        'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
        'sl' => Core\Secure::array2string(array('plan_id' => $row->id)),
        'undeletable' => $undeletable
      );
    }

    $response = array(
      'draw' => $draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsFiltered,
      'data' => $data
    );

    echo json_encode($response);
  }
}