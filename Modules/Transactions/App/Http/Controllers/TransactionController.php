<?php

namespace Modules\Transactions\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Trait\ActionButtonTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Transactions\App\Http\Requests\CreateTransactionRequest;
use Modules\Transactions\App\Http\Requests\CreateUserWalletRequest;
use Modules\Transactions\App\Http\Requests\CreateResellerWalletRequest;
use Modules\Transactions\App\Http\Requests\UpdateResellerWalletRequest;
use Modules\Transactions\App\Http\Requests\UpdateUserWalletRequest;
use Modules\Transactions\App\Http\Requests\UpdateTransactionRequest;
use Modules\Transactions\App\Repositories\TransactionRepositoryInterface;
use Modules\Transactions\App\Repositories\UserWalletRepositoryInterface;
use Modules\Transactions\App\Repositories\ResellerWalletRepositoryInterface;
use Modules\Users\App\Repositories\UserRepositoryInterface;
use Modules\Users\App\Repositories\UserGroupRepositoryInterface;
use Modules\Transactions\App\Trait\DataTableTrait;
use Yajra\DataTables\DataTables;
use Modules\Transactions\App\Models\UserWallet;
use Modules\Transactions\App\Models\ResellerWallet;

class TransactionController extends Controller
{
  use DataTableTrait;
  use ActionButtonTrait;

  protected TransactionRepositoryInterface $transactionRepository;
  protected UserRepositoryInterface $userRepository;

  public function __construct(
    UserWalletRepositoryInterface $userWalletRepository,
    ResellerWalletRepositoryInterface $resellerWalletRepository,
    TransactionRepositoryInterface $transactionRepository,
    UserGroupRepositoryInterface $userGroupRepository,
    UserRepositoryInterface $userRepository
  ) {
    $this->transactionRepository = $transactionRepository;
    $this->userWalletRepository = $userWalletRepository;
    $this->resellerWalletRepository = $resellerWalletRepository;
    $this->userGroupRepository = $userGroupRepository;
    $this->userRepository = $userRepository;
  }

  public function userWallet(Request $request)
  {
    $title = 'User Wallet List';
    $datas = $this->userWalletRepository->getUserWallet($request->all());

    // dd($datas->toArray());

    $ajaxUrl = route('uwallet-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => $row->user->name)
        ->addColumn('date', function ($row) {
            return \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i A'); // Example: "23/11/2024 07:55 AM"
        })
        //->addColumn('action', fn($row) => $this->editButton('uwallet-edit', $row->id) . ' ' . $this->deleteButton('uwallet-delete', $row->id))

        ->make();
    }

    $tableHeaders = $this->getTableHeader('users-wallet-list');
    $userLists = $this->userRepository->allUser();
    $userInfo = auth()->user();
    return view('transactions::uwallet.uwallet_list', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists', 'userInfo'));
  }

  public function onlineList(Request $request)
  {
    $title = 'Online transaction List';
    $datas = $this->userWalletRepository->getOnlineTransaction($request->all());

    // dd($datas->toArray());

    $ajaxUrl = route('online-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('username', fn($row) => $row->user->name)
        ->addColumn('created_at', function ($row) {
            return \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i A'); // Example: "23/11/2024 07:55 AM"
        })
        //->addColumn('action', fn($row) => $this->editButton('uwallet-edit', $row->id) . ' ' . $this->deleteButton('uwallet-delete', $row->id))

        ->make();
    }

    $tableHeaders = $this->getTableHeader('online-list');
    $userLists = $this->userRepository->allUser();
    $userInfo = auth()->user();
    // dd($datas->toArray()); 
    return view('transactions::online.online_list', compact('title', 'tableHeaders', 'ajaxUrl', 'userLists', 'userInfo'));
  }

  public function store(CreateUserWalletRequest $request)
  {
    $this->userWalletRepository->create($request->validated());
    return response()->json(['status' => 'created', 'message' => 'Wallet added successfully']);
  }


  public function edit($id)
  {
    $uwallet = $this->userWalletRepository->find($id);
    echo $uwallet;
  }


  public function update(UpdateUserWalletRequest $request, $id)
  {
    $this->userWalletRepository->update($request->validated(), $id);
    return response()->json(['status' => 'updated', 'message' => 'Wallet updated successfully']);
  }

  public function destroy($id)
  {
      $this->userWalletRepository->delete($id);
      return response()->json(['status' => 'deleted', 'message' => 'Wallet deleted successfully']);
  }

  /*public function userTransferList(Request $request)
  {
    $title = 'User Transfer List';
    $datas = $this->transactionRepository->getUserTransferList($request->all());
    $ajaxUrl = route('user-transfer-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('approved_date', fn($row) => $row->approved_date)
        ->addColumn('created_at', fn($row) => $row->created_at)
        ->addColumn('deposit_by', fn($row) => $row->depositBy->name ?? '-')
        ->addColumn('user_name', fn($row) => $row->user->name ?? '-')
        ->addColumn('deposit_amount', fn($row) => number_format($row->deposit_amount, 2))
        ->addIndexColumn()
        ->rawColumns(['created_at', 'approved_date'])
        ->make();
    }

    $tableHeaders = $this->getTableHeader('user-transfer-list');

    return view('transactions::user_transfer_list', compact('title', 'tableHeaders', 'ajaxUrl'));
  }


  //RESELLER WALLET------------------------------------------
  public function resellerWallet(Request $request)
  {
    $title = 'Reseller Wallet List';
    $datas = $this->resellerWalletRepository->getResellerWallet($request->all());
    $ajaxUrl = route('rwallet-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        //->addColumn('reseller_name', fn($row) => $row->user->name . ' (' . $row->user->username . ')' ?? '-')
        ->addColumn('username', fn($row) => $row->user->name . ' (' . $row->user->username . ')' ?? '-')
        //->addColumn('reseller', fn($row) => $row->user->reseller_name ?? '-')
        ->addColumn('action', fn($row) => $this->editButton('rwallet-edit', $row->id) . ' ' . $this->deleteButton('rwallet-delete', $row->id))
        ->make();
    }

    $tableHeaders = $this->getTableHeader('reseller-wallet-list');

    return view('transactions::reseller_wallet_list', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  public function reseller_wallet_store(CreateResellerWalletRequest $request)
  {
    $this->resellerWalletRepository->create($request->validated());
    return response()->json(['status' => 'added', 'message' => 'Wallet added successfully']);
  }

  public function reseller_wallet_edit($id)
  {
    $uwallet = $this->resellerWalletRepository->find($id);
    echo $uwallet;
  }

  public function reseller_wallet_update(UpdateResellerWalletRequest $request, $id)
  {
    $this->resellerWalletRepository->update($request->validated(), $id);
    return response()->json(['status' => 'updated', 'message' => 'Wallet updated successfully']);
  }

  public function reseller_wallet_destroy($id)
  {
      $this->resellerWalletRepository->delete($id);
      return response()->json(['status' => 'deleted', 'message' => 'Wallet deleted successfully']);
  }



  //RESELLER WALLET LIST
  public function resellerTransferList()
  {
    $title = 'Reseller Transfer List';
    $datas = $this->transactionRepository->all();
    $ajaxUrl = route('reseller-transfer-list');

    if ($this->ajaxDatatable()) {
      return DataTables::of($datas)
        ->addIndexColumn()
        ->addColumn('approved_date', fn($row) => $row->approved_date)
        ->addColumn('created_at', fn($row) => $row->created_at)
        ->addColumn('deposit_by', fn($row) => $row->depositBy->name ?? '-')
        ->addColumn('reseller_name', fn($row) => $row->user->name ?? '-')
        ->addColumn('deposit_amount', fn($row) => number_format($row->deposit_amount, 2))
        ->addIndexColumn()
        ->rawColumns(['created_at', 'approved_date'])
        ->make();
    }

    $tableHeaders = $this->getTableHeader('reseller-transfer-list');

    return view('transactions::reseller_transfer_list', compact('title', 'tableHeaders', 'ajaxUrl'));
  }

  //RESELLER TRANSFER LIST

  public function addResellerBalance()
  {
    $title = 'Add Reseller Balance';
    $users = $this->userRepository->getUserByGroupId($this->userGroupId);

    return view('transactions::add_reseller_balance', compact('title', 'users'));
  }

  //RESELLER BALANCE ADD

  public function storeResellerBalance(CreateTransactionRequest $request): RedirectResponse
  {
    $this->transactionRepository->create($request->validated());
    return redirect()->route('transaction.index')->with('success', 'Transaction added successfully');
  }

  //RESELLER BALANCE ADD

  public function editResellerBalance($id)
  {
    $title = 'Edit Reseller Balance';
    $transaction = $this->transactionRepository->find($id);
    $users = $this->userRepository->getUserByGroupId($this->userGroupId);

    return view('transactions::edit_reseller_balance', compact('title', 'transaction', 'users'));
  }

  //RESELLER BALANCE ADD

  public function updateResellerBalance(UpdateTransactionRequest $request, $id): RedirectResponse
  {
    $this->transactionRepository->update($request->validated(), $id);
    return redirect()->route('transaction.index')->with('success', 'Transaction updated successfully');
  }*/

  //RESELLER BALANCE ADD


}
