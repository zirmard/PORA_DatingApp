@extends('backend.layouts.main')

@php ($title = 'Logs')
@section('title') {{ $title }} @stop

<!--- Breadcrumb ---->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@stop

@push('css')

  <style>

    h1 {
      font-size: 1.5em;
      margin-top: 0;
    }

    #table-log {
        font-size: 0.85rem;
    }

    .btn {
        font-size: 0.7rem;
    }

    .stack {
      font-size: 0.85em;
    }

    .date {
      min-width: 75px;
    }

    .text {
      word-break: break-all;
    }

    a.llv-active {
      z-index: 2;
      background-color: #f5f5f5;
      border-color: #777;
    }

    .list-group-item {
      word-break: break-word;
    }

    .folder {
      padding-top: 15px;
    }
    .nowrap {
      white-space: nowrap;
    }
  </style>
@endpush

@section('content')
<div class="card laravel_log_container">
  <div class="card-body">
    <div class="row">
      <div class="col sidebar mb-3">

        
            <div class="custom-control custom-switch" style="display:none;">
              <input type="checkbox" class="custom-control-input" id="darkSwitch">
              <label class="custom-control-label" for="darkSwitch" style="margin-top: 6px;">Dark Mode</label>
            </div>
            
            
              @foreach($folders as $folder)
                <div class="list-group-item">
                  <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                    <span class="fa fa-folder"></span> {{$folder}}
                  </a>
                  @if ($current_folder == $folder)
                    <div class="list-group folder">
                      @foreach($folder_files as $file)
                        <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                          class="list-group-item @if ($current_file == $file) llv-active @endif">
                          {{$file}}
                        </a>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
              @foreach($files as $file)
                <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                  class="list-group-item @if ($current_file == $file) llv-active @endif">
                  {{$file}}
                </a>
              @endforeach
      </div>
    
    <div class="col-10 table-container">

          @if ($logs === null)
            <div>
              Log file >50M, please download it.
            </div>
          @else
          <div class="dt-responsive">
            <table id="table-log" class="table table-striped table-sm datatable" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
              <thead>
              <tr>
                @if ($standardFormat)
                  <th>Level</th>
                  <th>Context</th>
                  <th>Date</th>
                @else
                  <th>Line number</th>
                @endif
                <th>Content</th>
              </tr>
              </thead>
              <tbody>

              @foreach($logs as $key => $log)
                <tr data-display="stack{{{$key}}}">
                  @if ($standardFormat)
                    <td class="nowrap text-{{{$log['level_class']}}}">
                      <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                    </td>
                    <td class="text">{{$log['context']}}</td>
                  @endif
                  <td class="date">{{{$log['date']}}}</td>
                  <td class="text">
                    @if ($log['stack'])
                      <button type="button"
                              class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2"
                              data-display="stack{{{$key}}}">
                        <span class="fa fa-search"></span>
                      </button>
                    @endif
                    {{{$log['text']}}}
                    @if (isset($log['in_file']))
                      <br/>{{{$log['in_file']}}}
                    @endif
                    @if ($log['stack'])
                      <div class="stack" id="stack{{{$key}}}"
                          style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                      </div>
                    @endif
                  </td>
                </tr>
              @endforeach

              </tbody>
            </table>
          </div>
          @endif
          <div class="p-3">
            @if($current_file)
              <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-download"></span> Download file
              </a>
              -
              <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-sync"></span> Clean file
              </a>
              -
              <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                <span class="fa fa-trash"></span> Delete file
              </a>
              @if(count($files) > 1)
                -
                <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
                  <span class="fa fa-trash-alt"></span> Delete all files
                </a>
              @endif
            @endif
          </div>
    </div>
  </div>
  </div>
@stop

@push('script')
<!-- Datatables -->
<script src="{{ asset('theme/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('theme/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

  <script>
    function initTheme() {
      const darkThemeSelected =
        localStorage.getItem('darkSwitch') !== null &&
        localStorage.getItem('darkSwitch') === 'dark';
      darkSwitch.checked = darkThemeSelected;
      darkThemeSelected ? document.body.setAttribute('data-theme', 'dark') :
        document.body.removeAttribute('data-theme');
    }

    function resetTheme() {
      if (darkSwitch.checked) {
        document.body.setAttribute('data-theme', 'dark');
        localStorage.setItem('darkSwitch', 'dark');
      } else {
        document.body.removeAttribute('data-theme');
        localStorage.removeItem('darkSwitch');
      }
    }
  </script>

<script>

  // dark mode by https://github.com/coliff/dark-mode-switch
  const darkSwitch = document.getElementById('darkSwitch');

  // this is here so we can get the body dark mode before the page displays
  // otherwise the page will be white for a second... 
  initTheme();

  window.addEventListener('load', () => {
    if (darkSwitch) {
      initTheme();
      darkSwitch.addEventListener('change', () => {
        resetTheme();
      });
    }
  });

  // end darkmode js
        
  $(document).ready(function () {
    $('.table-container tr').on('click', function () {
      $('#' + $(this).data('display')).toggle();
    });
    $('#table-log').DataTable({
      "order": [$('#table-log').data('orderingIndex'), 'desc'],
      "stateSave": true,
      "stateSaveCallback": function (settings, data) {
        window.localStorage.setItem("datatable", JSON.stringify(data));
      },
      "stateLoadCallback": function (settings) {
        var data = JSON.parse(window.localStorage.getItem("datatable"));
        if (data) data.start = 0;
        return data;
      }
    });
    $('#delete-log, #clean-log, #delete-all-log').click(function () {
      return confirm('Are you sure?');
    });
  });
</script>
@endpush