<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Stripe\ApiResponse;
use Illuminate\Support\MessageBag;
use App\Notifications\NewRoleAssigned;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Junaidnasir\Larainvite\Facades\Invite as invite;
use App\Mail\InviteUser;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Junaidnasir\Larainvite\Models\LaraInviteModel as UserApiInvitation;

class UserApiController extends Controller
{
       public function index(Request $request)
    {
        // $this->restrict_user_types($request->type);

        $data['type'] = $request->type;

        if ($request->type == 'staff') {
            $data['entity'] = 'Writers';
            $data['entity_singular'] = 'Writer';
            $data['tag_id_list'] = Tag::orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        } else if ($request->type == 'admin') {
            $data['entity'] = 'Admins';
            $data['entity_singular'] = 'Admin';
        } else {
            $data['entity'] = 'Customers';
            $data['entity_singular'] = 'Customer';
        }


        if($data){

        return apiResponseSuccess($data,'Searching-dropdown');

        }else{
            return responseError( $data,'error');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function paginate(Request $request)
    {
        // $this->restrict_user_types($request->type);

        if ($request->type == 'customer') {
            $users = User::doesntHave('roles');
        } else if ($request->type == 'admin') {
            $users = User::role('admin');
        } else {
            $users = User::role('staff');
        }

        if ($request->inactive) {
            $users->where('inactive', 1);
        } else {
            $users->whereNull('inactive');
        }

        $users->orderBy('first_name', 'ASC');

        $dataTable = DataTables::eloquent($users)

        ->filter(function ($query) use ($request) {
            if ($request->search) {
                $terms = explode(" ", $request->search);
                foreach ($terms as $term) {
                    $query->where('first_name', 'like', '%' . $term . '%')
                        ->orWhere('last_name', 'like', '%' . $term . '%')
                        ->orWhere('email', 'like', '%' . $term . '%');
                }

                $query->orderByRaw("(first_name = '{$request->search}') desc, length(first_name)");
            }

            if ($request->tags) {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->whereIn('tags.id', $request->tags);
                });
            }
        })
        ->make(true);
        if($dataTable){
            return apiResponseSuccess($dataTable,'All Users');
        }else{
            return responseError($dataTable,'Error');
        }

    }

    public function send_invitation(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'role_type' => 'required|in:admin,staff'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                  ->withErrors($validator)
                  ->withInput();
        }

        $role_name = ($request->role_type == 'admin') ? 'Admin' : 'Writer';

        $users = User::where('email', $request->email)->get();

         if ($users->count() > 0) {
            $user = $users->first();

            // User already exists
            if ($user->hasRole($request->role_type)) {
                // Already an author
                return apiResponseSuccess( $user,'user is already assigned to this role');
            } else {
                // Assign the role
                $user->assignRole($request->role_type);

                $message = 'The email already exists and the role has been assigned to the user.';
             return  apiResponseSuccess($user,$message);
            }
        }

    }
 //user's Profile Methods

    public function userProfile(Request $request){

         $user_id=$request->id;

         $profile=User::with('ratings_received')->where('id',$user_id)->get();


       return apiResponseSuccess($profile,'User data');

    }

    public function update_profile(Request $request)
    {
        $user = User::find($request->id);

       if ($user) {
        $user->update([
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'timezone' => $request->input('timezone'),
        'bio' => $request->input('bio'),
        'address' => $request->input('address'),

      ]);
          return apiResponseSuccess($user, 'Successfully updated');
       }

    }


    public function change_password(Request $request)
    {
        $user_id = $request->id;
        $user = User::find($user_id);

        $currentPassword = $user->password;

        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) use ($currentPassword) {
                    if (!Hash::check($value, $currentPassword)) {
                        return $fail(__('Current password is not valid'));
                    }
                },
            ],
            'password' => 'required|confirmed',
        ]);


        $user->password = Hash::make($request->password);
        $user->save();

        return apiResponseSuccess('', 'Password successfully updated');

    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if(!$user){
            return apiResponseSuccess('', 'User Not Found');
        }

        $user->delete();
        return apiResponseSuccess('', 'User Deleted Successfully');

    }














    // public function show(User $user)
    // {
    //     $user->setMetaData();

    //     return apiResponseSuccess($user,'user detail');
    // }

}
