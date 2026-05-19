<style>
    .uni-status-cell { min-width: 190px; vertical-align: middle !important; }
    .uni-status-wrap { position: relative; display: inline-block; width: 100%; max-width: 220px; }
    .uni-app-status-select {
        width: 100%; appearance: none; border-radius: 999px; border: 1px solid #e4e6ef;
        background-color: #f9fafb; color: #3f4254; font-size: 12px; font-weight: 600;
        padding: 7px 34px 7px 14px; cursor: pointer;
        transition: border-color .2s, box-shadow .2s, background-color .2s, color .2s;
        box-shadow: 0 1px 2px rgba(24, 28, 50, .04);
    }
    .uni-app-status-select:hover { border-color: #b5b5c3; box-shadow: 0 2px 6px rgba(24, 28, 50, .08); }
    .uni-app-status-select:focus { outline: none; border-color: #3699ff; box-shadow: 0 0 0 3px rgba(54, 153, 255, .18); }
    .uni-status-wrap::after {
        content: "\f078"; font-family: "Font Awesome 5 Free"; font-weight: 900;
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        font-size: 10px; color: #7e8299; pointer-events: none;
    }
    .uni-status-wrap.status-tone-success .uni-app-status-select { background: #e8fff3; border-color: #50cd89; color: #0d9f5f; }
    .uni-status-wrap.status-tone-danger .uni-app-status-select { background: #fff5f8; border-color: #f1416c; color: #d9214e; }
    .uni-status-wrap.status-tone-warning .uni-app-status-select { background: #fff8dd; border-color: #ffc700; color: #b78103; }
    .uni-status-wrap.status-tone-primary .uni-app-status-select { background: #f1faff; border-color: #3699ff; color: #187de4; }
    .uni-app-status-select.is-saving { opacity: .65; cursor: wait; pointer-events: none; }
    .uni-app-status-select.is-saved { box-shadow: 0 0 0 3px rgba(80, 205, 137, .22); }
    .uni-status-saving { position: absolute; right: 28px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #7e8299; display: none; }
    .uni-status-wrap.is-busy .uni-status-saving { display: inline-block; }
    .uni-status-wrap.is-busy::after { opacity: .35; }
</style>
