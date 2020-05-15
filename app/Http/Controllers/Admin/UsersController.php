<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Services\Admin\Interfaces\UserServiceInterface;

class UsersController extends Controller
{

    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * users - index[GET] view all users is actived and not admin
     *
     * @return view
     */
    public function index()
    {
        $listUser = $this->userService->getUsers($paginate = true);
        return view('admin.users.index', compact('listUser'));
    }

    /**
     * users - create[GET] show view to create a new user
     *
     * @return void
     */
    public function create()
    {
        $listUser = $this->userService->getUsers();
        return view('admin.users.create', compact('listUser'));
    }

    /**
     * users - store[POST] action to create new user
     *
     * @param CreateUserRequest $request
     * @return void
     */
    public function store(CreateUserRequest $request)
    {
        $userData = $request->only(['email', 'username', 'description', 'image']);
        
        // create new user
        $user = $this->userService->createUser($userData);

        if ($user) {
            // if creaet user success then register receivers who want to receive status from this user
            $leader = $request->input('leader');
            $receiverNotification = $request->input('listUser') ?? [];
            if ($leader && !in_array($leader, $receiverNotification)) {
                $receiverNotification[] = $leader;
            }

            $this->userService->registReceiverNotification($user, $receiverNotification);
        }

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view("admin.users.edit", compact('user'));
    }

    public function update(EditUserRequest $request, User $user)
    {
        $data = $request->only(['username', 'email', 'password']);

        $resUpdate = $this->userService->updateUser($user, $data);

        if (!$resUpdate) {
            return back()->withErrors(["msg" => "Something error while update. Please try again"]);
        }
        
        return redirect()->route('admin.users.index');
    }

    public function delete(User $user)
    {
        $isDeleted = $user->delete();

        if (!$isDeleted) {
            return back()->withErrors(["msg" => "Something error while update. Please try again"]);
        }
        
        return redirect()->route('admin.users.index');
    }

}
