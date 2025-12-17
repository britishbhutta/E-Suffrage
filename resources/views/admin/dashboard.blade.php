@extends('admin.layout.master')
@section('title')
home
@endsection

@section('content')
<div class="pagetitle">
  <h1>Dashboard</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
  <div class="row">

    <div class="col-lg-12">
      <div class="row">

        <div class="col-xxl-4 col-md-4">
          <div class="card info-card sales-card">
            <div class="card-body">
              <h5 class="card-title">Voters</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fa-solid fa-person-booth"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $voterCount }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-md-4">
          <div class="card info-card revenue-card">
            <div class="card-body">
              <h5 class="card-title">Poll Creators</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fa-solid fa-user-pen"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $creatorCount }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

         <div class="col-xxl-4 col-md-4">
          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">Total Poll</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $totalPolls }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-md-4">
          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">Completed Polls</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $completedPolls }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-md-4">
          <div class="card info-card customers-card">
            <div class="card-body">
              <h5 class="card-title">Active Polls</h5>
              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-info">
                  <i class="fa-solid fa-broadcast-tower text-white"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ $runningPolls }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>
@endsection
