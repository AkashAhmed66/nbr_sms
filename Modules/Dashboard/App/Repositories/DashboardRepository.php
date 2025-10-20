<?php
namespace Modules\Dashboard\App\Repositories;
use Illuminate\Database\Eloquent\Collection;
use Modules\Dashboard\App\Models\Dashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Transactions\App\Models\UserWallet;
use Modules\Users\App\Models\User;
use Modules\Messages\App\Models\Message;
use Modules\Messages\App\Models\Outbox;
use Modules\Smsconfig\App\Models\SenderId;
use Modules\Transactions\App\Models\Transaction;
use Carbon\Carbon;

class DashboardRepository implements DashboardRepositoryInterface
{
  protected $model;
  private $user_id;

  public function __construct(Dashboard $model)
  {
    $this->model = $model;
    $this->user_id = Auth::user()->id ?? null;
    $this->user_group_id = Auth::user()->id_user_group ?? null;
  }

  public function all(array $filters = []): Collection
  {
    $query = $this->model->query();

    if (isset($filters['title'])) {
      $query->where('title', 'like', '%' . $filters['title'] . '%');
    }

    if (isset($filters['content'])) {
      $query->where('content', 'like', '%' . $filters['content'] . '%');
    }

    return $query->get();
  }

  public function create(array $data): Dashboard
  {
    return $this->model->create($data);
  }

  public function update(array $data, int $id): Dashboard
  {
    $dashboard = $this->model->find($id);
    $dashboard->update($data);

    return $dashboard;
  }

  public function find(int $id): Dashboard
  {
    return $this->model->find($id);
  }

  public function delete(int $id): bool
  {
    return $this->model->destroy($id);
  }

  public function countTotalUsers(): int
  {
    if($this->user_group_id != 1){
      return User::where('created_by', $this->user_id)->count();
    }else{
      return User::count();
    }
  }

  public function calculateUserPercentage()
  {
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
    $currentMonthStart = Carbon::now()->startOfMonth();
    $currentMonthEnd = Carbon::now()->endOfMonth();

    $result = User::where('id', $this->user_id)
      ->selectRaw("
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_month_users,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as current_month_users
        ", [$lastMonthStart, $lastMonthEnd, $currentMonthStart, $currentMonthEnd])
      ->first();

    $lastMonthUsers = $result->last_month_users;
    $currentMonthUsers = $result->current_month_users;

    if ($lastMonthUsers == 0) {
      $percentageChange = $currentMonthUsers > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100;
    }

    if ($currentMonthUsers > $lastMonthUsers) {
      $formattedPercentageChange = '+' . round($percentageChange, 2) . '%';
    } elseif ($currentMonthUsers < $lastMonthUsers) {
      $formattedPercentageChange = '-' . round(abs($percentageChange), 2) . '%';
    } else {
      $formattedPercentageChange = '0%'; // No change if the numbers are equal
    }


    return [
      'last_month_users' => $lastMonthUsers,
      'current_month_users' => $currentMonthUsers,
      'percentage_change' => $formattedPercentageChange
    ];
  }

  public function countTotalMessages(): int
  {
    if(auth()->user()->id_user_group == 1){
      return Outbox::where('status', '!=', 'REJECTD')
          ->where('status', '!=', 'PENDING')
          ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
          ->sum('smscount');
    }else if(auth()->user()->id_user_group == 2){
      $ids = User::where('created_by', auth()->user()->id)->pluck('id')->toArray();
      $ids[] = auth()->user()->id;

      return Outbox::whereIn('user_id', $ids)
          ->where('status', '!=', 'REJECTD')
          ->where('status', '!=', 'PENDING')
          ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
          ->sum('smscount');
    }else{
      return Outbox::where('user_id', auth()->user()->id)
          ->where('status', '!=', 'REJECTD')
          ->where('status', '!=', 'PENDING')
          ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
          ->sum('smscount');
    }
  }

  public function countTotalMessagesDay(): int
  {
    if(auth()->user()->id_user_group == 1){
      return Outbox::where('status', '!=', 'REJECTD')
          ->where('status', '!=', 'PENDING')
          ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
          ->sum('smscount');
    }else if(auth()->user()->id_user_group == 2){
      $ids = User::where('created_by', auth()->user()->id)->pluck('id')->toArray();
      $ids[] = auth()->user()->id;

      return Outbox::whereIn('user_id', $ids)
          ->where('status', '!=', 'REJECTD')
          ->where('status', '!=', 'PENDING')
          ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
          ->sum('smscount');
    }else{
      return Outbox::where('user_id', auth()->user()->id)
          ->where('status', '!=', 'REJECTD')
          ->where('status', '!=', 'PENDING')
          ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
          ->sum('smscount');
    }
  }
  public function calculateMessagePercentage()
  {
    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
    $currentMonthStart = Carbon::now()->startOfMonth();
    $currentMonthEnd = Carbon::now()->endOfMonth();

    $result = Outbox::where('user_id', $this->user_id)->selectRaw("
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_month_message,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as current_month_message
        ", [$lastMonthStart, $lastMonthEnd, $currentMonthStart, $currentMonthEnd])
      ->first();

    $lastMonthMessage = $result->last_month_message;
    $currentMonthMessage = $result->current_month_message;

    if ($lastMonthMessage == 0) {
      $percentageChange = $currentMonthMessage > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentMonthMessage - $lastMonthMessage) / $lastMonthMessage) * 100;
    }

    if ($currentMonthMessage > $lastMonthMessage) {
      $formattedPercentageChange = '+' . round($percentageChange, 2) . '%';
    } elseif ($currentMonthMessage < $lastMonthMessage) {
      $formattedPercentageChange = '-' . round(abs($percentageChange), 2) . '%';
    } else {
      $formattedPercentageChange = '0%'; // No change if the numbers are equal
    }


    return [
      'last_month_message' => $lastMonthMessage,
      'current_month_message' => $currentMonthMessage,
      'percentage_change' => $formattedPercentageChange
    ];
  }

  public function countTotalTransections(): int
  {
    if($this->user_group_id == 1){
      return UserWallet::sum('balance');
    }
    return UserWallet::where('user_id', $this->user_id)->sum('balance');
  }

  public function calculateTransecionPercentage()
  {

    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
    $currentMonthStart = Carbon::now()->startOfMonth();
    $currentMonthEnd = Carbon::now()->endOfMonth();

    if($this->user_group_id == 1){
      $result = UserWallet::selectRaw("
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN balance ELSE 0 END) as last_month_amount,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN balance ELSE 0 END) as current_month_amount
        ", [$lastMonthStart, $lastMonthEnd, $currentMonthStart, $currentMonthEnd])
        ->first();
    }else{
      $result = UserWallet::where('user_id', $this->user_id)->selectRaw("
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN balance ELSE 0 END) as last_month_amount,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN balance ELSE 0 END) as current_month_amount
        ", [$lastMonthStart, $lastMonthEnd, $currentMonthStart, $currentMonthEnd])
        ->first();
    }

    $lastMonthAmount = $result->last_month_amount;
    $currentMonthAmount = $result->current_month_amount;

    // Calculate percentage change for amount
    if ($lastMonthAmount == 0) {
      $amountPercentageChange = $currentMonthAmount > 0 ? 100 : 0;
    } else {
      $amountPercentageChange = (($currentMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100;
    }

    // Format amount percentage change
    if ($currentMonthAmount > $lastMonthAmount) {
      $formattedAmountPercentageChange = '+' . round($amountPercentageChange, 2) . '%';
    } elseif ($currentMonthAmount < $lastMonthAmount) {
      $formattedAmountPercentageChange = '-' . round(abs($amountPercentageChange), 2) . '%';
    } else {
      $formattedAmountPercentageChange = '0%'; // No change if the amounts are equal
    }

    return [
      'last_month_amount' => $lastMonthAmount,
      'current_month_amount' => $currentMonthAmount,
      'percentage_change' => $formattedAmountPercentageChange
    ];

  }


  public function countTotalSenderId(): int
  {
    return SenderId::count();
    if($this->user_group_id == 1){
      return SenderId::count();
    }
    return SenderId::where('user_id', $this->user_id)->count();
  }

  public function calculateSenderIdPercentage()
  {

    $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
    $currentMonthStart = Carbon::now()->startOfMonth();
    $currentMonthEnd = Carbon::now()->endOfMonth();

    $result = SenderId::where('user_id', $this->user_id)->selectRaw("
        SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as last_month_senderid,
        SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as current_month_senderid
    ", [
      $lastMonthStart, $lastMonthEnd,
      $currentMonthStart, $currentMonthEnd
    ])->first();

    $lastMonthAmount = $result->last_month_senderid;
    $currentMonthAmount = $result->current_month_senderid;

    // Calculate percentage change for amount
    if ($lastMonthAmount == 0) {
      $amountPercentageChange = $currentMonthAmount > 0 ? 100 : 0;
    } else {
      $amountPercentageChange = (($currentMonthAmount - $lastMonthAmount) / $lastMonthAmount) * 100;
    }

    // Format amount percentage change
    if ($currentMonthAmount > $lastMonthAmount) {
      $formattedAmountPercentageChange = '+' . round($amountPercentageChange, 2) . '%';
    } elseif ($currentMonthAmount < $lastMonthAmount) {
      $formattedAmountPercentageChange = '-' . round(abs($amountPercentageChange), 2) . '%';
    } else {
      $formattedAmountPercentageChange = '0%'; // No change if the amounts are equal
    }

    return [
      'last_month_amount' => $lastMonthAmount,
      'current_month_amount' => $currentMonthAmount,
      'percentage_change' => $formattedAmountPercentageChange
    ];

  }

  public function getStatusWiseMessages(array $filters = []): Collection
  {

    $query = Outbox::query();

    $query->whereDate('created_at', Carbon::today()->toDateString());

    $totalMessages = $query->count();
    $messageCounts = $query->select('status', DB::raw('count(*) as count'))
      ->groupBy('status')
      ->get();
    $messageCountsWithPercentage = $messageCounts->map(function ($item) use ($totalMessages) {
      $item->percentage = round(($item->count / $totalMessages) * 100, 2);
      return $item;
    });

    return $messageCountsWithPercentage;
  }

  public function getLast7DaysTransections(array $filters = []): Collection
  {

    $transactionsQuery  = UserWallet::selectRaw('DATE(created_at) as date, DATE_FORMAT(created_at, "%a") as day, COUNT(*) as total_transactions, SUM(balance) as total_amount')
      ->where('created_at', '>=', Carbon::now()->subDays(7));
	  if($this->user_group_id !=1 || $this->user_group_id !=2){
		  $transactionsQuery ->where('user_id', $this->user_id);
	  }

      $transactions = $transactionsQuery->groupByRaw('DATE(created_at), DATE_FORMAT(created_at, "%a")')
		  ->orderByRaw('DATE(created_at)')
		  ->get();

    return $transactions;
  }

  public function getLast7DaysTransectionsAmount()
  {

	$currentAmount = UserWallet::selectRaw('SUM(balance) as total_amount')
		  ->where('created_at', '>=', Carbon::now()->subDays(7))
		  ->where('created_at', '<=', Carbon::now());
	  if($this->user_group_id !=1 || $this->user_group_id !=2){
		  $currentAmount->where('user_id', $this->user_id);
	  }
    $currentAmount = $currentAmount->first();

    $previousAmount = UserWallet::selectRaw('SUM(balance) as total_amount')
      ->where('created_at', '>=', Carbon::now()->subDays(14))
      ->where('created_at', '<', Carbon::now()->subDays(7));
	  if($this->user_group_id !=1 || $this->user_group_id !=2){
			  $previousAmount->where('user_id', $this->user_id);
		  }
      $previousAmount = $previousAmount->first();

    // Calculate the percentage change
    $currentTotalAmount = $currentAmount->total_amount ?? 0;
    $previousTotalAmount = $previousAmount->total_amount ?? 0;

    if ($previousTotalAmount == 0) {
      $percentageChange = $currentTotalAmount > 0 ? 100 : 0; // If previous amount is 0, and current is greater than 0, it's 100%.
    } else {
      $percentageChange = (($currentTotalAmount - $previousTotalAmount) / $previousTotalAmount) * 100;
    }

    // Format the percentage change with a + or - sign
    $formattedPercentageChange = $percentageChange > 0
      ? '+' . round($percentageChange, 2) . '%'
      : ($percentageChange < 0
        ? '-' . round(abs($percentageChange), 2) . '%'
        : '0%');

    // Return the result
    return [
      'current_total_amount' => $currentTotalAmount,
      'previous_total_amount' => $previousTotalAmount,
      'percentage_change' => $formattedPercentageChange
    ];
  }

  public function getLast7DaysMessages(array $filters = []): Collection
  {

    $messagesQuery = Outbox::selectRaw('DATE(created_at) as date, DATE_FORMAT(created_at, "%a") as day, COUNT(*) as total_messages')
		->where('created_at', '>=', Carbon::now()->subDays(7))
		->groupByRaw('DATE(created_at), DATE_FORMAT(created_at, "%a")');

		if($this->user_group_id !=1 || $this->user_group_id !=2){
			$messagesQuery->where('user_id', $this->user_id);
		}

	$messages = $messagesQuery->get();

	return $messages;

  }

  public function getLast7DaysMessagesTotal()
  {

    $currentCount = Outbox::selectRaw('COUNT(*) as total_count')
		  ->where('created_at', '>=', Carbon::now()->subDays(7))
		  ->where('created_at', '<=', Carbon::now());
	  if($this->user_group_id !=1 || $this->user_group_id !=2){
		  $currentCount->where('user_id', $this->user_id);
	  }
    $currentCount = $currentCount->first();


    $previousCount = Outbox::selectRaw('COUNT(*) as total_count')
		  ->where('created_at', '>=', Carbon::now()->subDays(14))
		  ->where('created_at', '<', Carbon::now()->subDays(7));
	  if($this->user_group_id !=1 || $this->user_group_id !=2){
		  $previousCount->where('user_id', $this->user_id);
	  }
	$previousCount = $previousCount->first();

    $currentTotalCount = $currentCount->total_count ?? 0;
    $previousTotalCount = $previousCount->total_count ?? 0;

    if ($previousTotalCount == 0) {
      $percentageChange = $currentTotalCount > 0 ? 100 : 0;
    } else {
      $percentageChange = (($currentTotalCount - $previousTotalCount) / $previousTotalCount) * 100;
    }

    $formattedPercentageChange = $percentageChange > 0
      ? '+' . round($percentageChange, 2) . '%'
      : ($percentageChange < 0
        ? '-' . round(abs($percentageChange), 2) . '%'
        : '0%');

    return [
      'current_total_count' => $currentTotalCount,
      'previous_total_count' => $previousTotalCount,
      'percentage_change' => $formattedPercentageChange
    ];
  }

  public function countTotalSentMessages(): int
  {
      $today = Carbon::today();
      return Outbox::whereDate('created_at', $today)
          ->where('user_id', $this->user_id)
          //->where('status', 'ACCEPTD')
          ->count();
  }

  public function countTotalFailedMessages()
  {
      //Get Reason
      return Outbox::where('status', 'FAILED')
          ->whereDate('created_at', Carbon::today())
          ->select('remarks as reason', DB::raw('COUNT(*) as count'))
          ->groupBy('remarks')
          ->get()->toArray();

  }

}
