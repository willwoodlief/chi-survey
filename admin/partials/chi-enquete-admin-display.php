<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/admin/partials
 */
?>

<style>
    .slick-row {
        line-height: 16px;
    }

    .loading-indicator {
        display: inline-block;
        padding: 12px;
        background: white;
        -opacity: 0.5;
        color: black;
        font-weight: bold;
        z-index: 9999;
        border: 1px solid red;
        -moz-border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-box-shadow: 0 0 5px red;
        -webkit-box-shadow: 0px 0px 5px red;
        -text-shadow: 1px 1px 1px white;
    }

    .loading-indicator label {
        padding-left: 20px;
        background: url('http://6pac.github.io/SlickGrid/images/ajax-loader-small.gif') no-repeat center left;
    }
</style>

<div class="chi-enquete-admin">
    <div class="chi-enquete-table">
        <div style="width:770px;height:700px;float:left;">
            <div class="grid-header" style="width:100%">
                <label>Survey Results Search</label>
                <span style="float:right;display:inline-block;">
                    Search Anonomous Key (partial or full) [press enter]: <input type="text" id="txtSearch" value="">
                </span>
            </div>
            <div id="myGrid" style="width:100%;height:600px;"></div>
            <div id="pager" style="width:100%;height:20px;"></div>
        </div>


    </div>
    <div class="chi-enquete-detail">
        <div class="chi-enquete-stats">
            <span style="margin-bottom: 0.25em;font-weight: bold;font-size: larger"> Overall Stats for all Reports</span>
        </div>
        <div class="chi-enquete-details-here">

        </div>
    </div>
</div>



<script>
    var grid, s;
    var loader = new Slick.Data.RemoteModel();
    var dobFormatter = function (row, cell, value, columnDef, dataContext) {
        let d = new Date(dataContext.dob_ts * 1000);
        let s = '<span>' + d.toLocaleDateString()+  '</span>';
        return s;
    };

    var dateFormatter = function (row, cell, value, columnDef, dataContext) {
        let d = new Date(dataContext.created_at_ts * 1000);
        let s = '<span>' + d.toLocaleDateString()+  '</span>';
        return s;
    };
    var brandFormatter = function (row, cell, value, columnDef, dataContext) {
        return dataContext.brand.name;
    };
    var my_columns = [
        {id: "autonomie", name: "autonomie", field: "autonomie", formatter: null, width: 90, sortable: true},
        {id: "competentie", name: "competentie", field: "competentie", formatter: null, width: 100, sortable: true},
        {id: "sociale_verbondenheid", name: "sociale", field: "sociale_verbondenheid", formatter: null, width: 65, sortable: true},
        {id: "fysieke_vrijheid", name: "fysieke", field: "fysieke_vrijheid", formatter: null, width: 75, sortable: true},
        {id: "emotioneel_welbevinden", name: "welbevinden", field: "emotioneel_welbevinden", formatter: null, width: 100, sortable: true},
        {id: "energie", name: "energie", field: "energie", formatter: null, width: 70, sortable: true},
        {id: "created_at_ts", name: "created at", field: "created_at_ts", formatter: dateFormatter, width: 90, sortable: true},
        {id: "anon_key", name: "anon key", field: "anon_key", formatter: null, width: 80, sortable: true},
        {id: "dob_ts", name: "dob", field: "dob_ts", formatter: dobFormatter, width: 90, sortable: true},

    ];
    var options = {
        rowHeight: 21,
        editable: false,
        enableAddRow: false,
        enableCellNavigation: false,
        enableColumnReorder: false
    };
    var loadingIndicator = null;
    jQuery(function () {
        console.log("body js fired");
        grid = new Slick.Grid("#myGrid", loader.data, my_columns, options);



        grid.onViewportChanged.subscribe(function (e, args) {
            var vp = grid.getViewport();
            loader.ensureData(vp.top, vp.bottom);
        });
        grid.onSort.subscribe(function (e, args) {
            loader.setSort(args.sortCol.field, args.sortAsc ? 1 : -1);
            var vp = grid.getViewport();
            loader.ensureData(vp.top, vp.bottom);
        });
        loader.onDataLoading.subscribe(function () {
            if (!loadingIndicator) {
                loadingIndicator = jQuery("<span class='loading-indicator'><label>Buffering...</label></span>").appendTo(document.body);
                var $g = jQuery("#myGrid");
                loadingIndicator
                    .css("position", "absolute")
                    .css("top", $g.position().top + $g.height() / 2 - loadingIndicator.height() / 2)
                    .css("left", $g.position().left + $g.width() / 2 - loadingIndicator.width() / 2);
            }
            loadingIndicator.show();
        });
        loader.onDataLoaded.subscribe(function (e, args) {
            for (var i = args.from; i <= args.to; i++) {
                grid.invalidateRow(i);
            }
            grid.updateRowCount();
            grid.render();
            loadingIndicator.fadeOut();
        });
        jQuery("#txtSearch").keyup(function (e) {
            if (e.which == 13) {
                loader.setSearch(jQuery(this).val());
                var vp = grid.getViewport();
                loader.ensureData(vp.top, vp.bottom);
            }
        });
        loader.setSearch(jQuery("#txtSearch").val());
        loader.setSort("created_at", -1);
        grid.setSortColumn("created_at", false);
        // load the first page
        grid.onViewportChanged.notify();

        grid.onClick.subscribe(function (e, args) {
            grid.setSelectedRows([args.row]);
            var tim = grid.getDataItem(args.row);
            chi_enquete_talk_to_backend('detail', {id:tim.id}, function (data){
                jQuery('div.chi-enquete-details-here').html(data.html);
            });

        });

        grid.setSelectionModel(new Slick.RowSelectionModel({
            selectActiveRow: false
        }));
        // grid.invalidateAllRows();
        // grid.invalidate();
        // grid.render();
    })
</script>
