$(function() {
    $(".sidebar").sideNav();
    $('.modal').modal();
    $('.container').on('chip.add', ".chips", function(e, chip) {
        $("input[name=datachip]").val(JSON.stringify($(this).material_chip('data')));
    });
    $('.container').on('chip.delete', ".chips", function(e, chip) {
        $("input[name=datachip]").val(JSON.stringify($(this).material_chip('data')));
    });
    $('.container').on('chip.add', ".addproc", function(e, chip) {
        $("input[name=addpro]").val(JSON.stringify($(this).material_chip('data')));
    });
    $('.container').on('chip.delete', ".addproc", function(e, chip) {
        $("input[name=addpro]").val(JSON.stringify($(this).material_chip('data')));
    });
    $(".container").on("change", ".datepicker", function() {
        $(this).val(moment($(this).val(), "DD MMM,YYYY").format("YYYY-M-D"));
    });

    function formelement() {
        Materialize.updateTextFields();
        $('.collapsible').collapsible();
        $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 10
        });
        $('.chips-placeholder').material_chip({
            placeholder: 'Enter',
            secondaryPlaceholder: 'Type And Enter'
        });
        $('select').material_select();
        $('.modal').modal();
    }

    function validat_setup() {
        if ($("input[name=project_name]").val().length < 3) {
            swal("Warning", "Please Specify Project Name", "warning");
        } else if ($("input[name=str_time]").val().length < 1 || moment($("input[name=str_time]").val(), "YYYY-M-D").isAfter($("input[name=str_endd]").val(), "YYYY-M-D")) {
            swal("Warning", "Please Specify Date Start", "warning");
        } else if ($("input[name=str_end]").val().length < 1 || moment($("input[name=str_end]").val(), "YYYY-M-D").isBefore($("input[name=str_time]").val(), "YYYY-M-D")) {
            swal("Warning", "Please Specify Date End", "warning");
        } else if ($("select[name=department]").val().length < 1) {
            swal("Warning", "Please Specify Department", "warning");
        } else if ($("textarea[name=Description]").val().length < 1) {
            swal("Warning", "Please Specify Description", "warning");
        } else if ($("input[name=owner]").val().length < 2) {
            swal("Warning", "Please Specify Owner", "warning");
        } else if ($("input[name=datachip]").val().length < 3) {
            swal("Warning", "Please Specify Process", "warning");
        } else {
            $("input[name=departvalue]").val($("select[name=department]").val());
            $.ajax({
                url: "manage/setupflow/savesetup.php",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($(".setup")[0]),
                success: function(data) {
                    if (data === "Saved") {
                        swal({
                            title: 'Success',
                            text: data,
                            type: "success"
                        }, function() {
                            location.reload();
                        });
                    } else {
                        swal("Error", data, "error");
                    }
                }
            });
        }
    }

    function validat_dupicate() {
        if ($("input[name=project_named]").val().length < 3) {
            swal("Warning", "Please Specify Project Name", "warning");
        } else if ($("input[name=str_timed]").val().length < 1 || moment($("input[name=str_timed]").val(), "D MMM, YYYY").isAfter($("input[name=str_endd]").val(), "D MMM, YYYY")) {
            swal("Warning", "Please Specify Date Start", "warning");
        } else if ($("input[name=str_endd]").val().length < 1 || moment($("input[name=str_endd]").val(), "D MMM, YYYY").isBefore($("input[name=str_timed]").val(), "D MMM, YYYY")) {
            swal("Warning", "Please Specify Date End", "warning");
        } else if ($("textarea[name=Descriptiond]").val().length < 1) {
            swal("Warning", "Please Specify Description", "warning");
        } else if ($("input[name=ownerd]").val().length < 2) {
            swal("Warning", "Please Specify Owner", "warning");
        } else {
            $.ajax({
                url: "manage/dupicate/new_dup.php",
                type: 'POST',
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($(".duppro")[0]),
                success: function(data) {
                    if (data === "Saved") {
                        swal({
                            title: 'Success',
                            text: data,
                            type: "success"
                        }, function() {
                            location.reload();
                        });
                    } else {
                        swal("Error", data, "error");
                    }
                }
            });
        }
    }

    function flowview(id) {
        $.ajax({
            url: "manage/setting/workfset.php",
            type: 'POST',
            data: {
                id: id
            },
            success: function(data) {
                $("#flowpreview").html(data);
                runflow(id);
            }
        });
    }

    function runflow(id) {
        $.ajax({
            url: 'manage/setting/datalineflow.php',
            dataType: "json",
            data: {
                id: id
            },
            success: function(data) {
                if (data.length < 1) {
                    return false;
                }
                var jscm = [];
                var po3 = $("#canvas").offset();
                for (var i of data) {
                    var po1 = $(i.sou).offset();
                    var po2 = $(i.des).offset();
                    if (i.type == 1) {
                        jscm[jscm.length] = {
                            data: [{
                                    x: Math.round((po1.left - po3.left) + ($(i.sou).width() / 2)),
                                    y: Math.round((po1.top - po3.top) + ($(i.sou).height() / 2))
                                },
                                {
                                    x: Math.round((po2.left - po3.left) + ($(i.des).width() / 2)),
                                    y: Math.round((po2.top - po3.top) + ($(i.des).height() / 2))
                                }
                            ]
                        };
                    } else if (i.type == 2) {
                        jscm[jscm.length] = {
                            data: [{
                                    x: Math.round((po1.left - po3.left) + ($(i.sou).width() / 2)),
                                    y: Math.round((po1.top - po3.top) + $(i.sou).height())
                                },
                                {
                                    x: Math.round((po1.left - po3.left) + ($(i.sou).width() / 2)),
                                    y: Math.round((po2.top - po3.top) + ($(i.des).height() / 2))
                                },
                                {
                                    x: Math.round((po2.left - po3.left) + ($(i.des).width() / 2)),
                                    y: Math.round((po2.top - po3.top) + ($(i.des).height() / 2))
                                }
                            ]
                        };
                    } else if (i.type == 3) {
                        var mp = parseFloat(($(i.sou).css("margin-right")).slice(0, -2)) + parseFloat(($(i.sou).css("padding-right")).slice(0, -2));
                        jscm[jscm.length] = {
                            data: [{
                                    x: Math.round((po1.left - po3.left) + $(i.sou).width()),
                                    y: Math.round((po1.top - po3.top) + ($(i.sou).height() / 2))
                                },
                                {
                                    x: Math.round(((po1.left - po3.left) + ($(i.sou).width())) + (mp)),
                                    y: Math.round((po1.top - po3.top) + ($(i.sou).height() / 2))
                                },
                                {
                                    x: Math.round(((po1.left - po3.left) + ($(i.sou).width())) + (mp)),
                                    y: Math.round((po2.top - po3.top) + ($(i.des).height() / 2))
                                },
                                {
                                    x: Math.round((po2.left - po3.left) + ($(i.des).width() / 2)),
                                    y: Math.round((po2.top - po3.top) + ($(i.des).height() / 2))
                                }
                            ]
                        };
                    } else if (i.type == 4) {
                        jscm[jscm.length] = {
                            data: [{
                                    x: Math.round((po1.left - po3.left) + ($(i.sou).width() / 2)),
                                    y: Math.round((po1.top - po3.top) + $(i.sou).height())
                                },
                                {
                                    x: Math.round((po1.left - po3.left) + ($(i.sou).width() / 2)),
                                    y: Math.round(((po1.top - po3.top) + $(i.sou).height()) + ((po2.top - po3.top) / 9))
                                },
                                {
                                    x: Math.round((po2.left - po3.left) + ($(i.des).width() / 2)),
                                    y: Math.round(((po1.top - po3.top) + $(i.sou).height()) + ((po2.top - po3.top) / 9))
                                },
                                {
                                    x: Math.round((po2.left - po3.left) + ($(i.des).width() / 2)),
                                    y: Math.round((po2.top - po3.top) + ($(i.des).height() / 2))
                                }
                            ]
                        };
                    } else {

                    }
                }
                flow(jscm);
            }
        });
    }

    function timelinesh(id) {
        $.ajax({
            url: 'manage/jquery/tm.php',
            type: 'POST',
            dataType: 'script',
            data: {
                id: id
            }
        });
    }
    $(".dupm").on('submit', '.duppro', function(event) {
        event.preventDefault();
        validat_dupicate();
    });
    $(".container").on("submit", ".setup", function(e) {
        e.preventDefault();
        validat_setup();
    });
    $(".addproject").on("click", function(e) {
        e.preventDefault();
        $.ajax({
            url: "manage/setupflow/form.php",
            type: 'POST',
            success: function(data) {
                $(".container").html(data);
                formelement();
                $.getJSON('manage/setupflow/shname.php', function(json, textStatus) {
                    $("#owner").autocomplete({
                        data: json
                    });
                });
            }
        });
    });
    $(".showfw").on('click', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: 'manage/view/showworkflow.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function(data) {
                $(".container").html(data);
                runflow(id);
                timelinesh(id);
                $(".modal").modal();
            }
        });
    });
    $(".container").on('click', ".showfw", function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: 'manage/view/showworkflow.php',
            type: 'POST',
            data: {
                id: id
            },
            success: function(data) {
                $(".container").html(data);
                runflow(id);
                timelinesh(id);
                $(".modal").modal();
            }
        });
    });
    $(".setting").on("click", function(e, pa1) {
        e.preventDefault();
        if (pa1 == null) {
            var id = $(this).attr("data-id");
        } else {
            var id = pa1;
        }
        $.ajax({
            url: "manage/setting/from.php",
            type: 'POST',
            data: {
                id: id
            },
            success: function(data) {
                $(".container").html(data);
                formelement();
                flowview(id);
                $.getJSON('manage/task/mempro.php', {
                    idj: id
                }, function(json) {
                    $("#ownered").autocomplete({
                        data: json
                    });
                });
            }
        });
    });
    $(".infof").on('click', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: 'manage/info/info.php',
            data: {
                id: id
            },
            success: function(data) {
                $(".container").html(data);
                $('.collapsible').collapsible();
                $('.modal').modal();
            }
        });

    });
    $(".delproj").on("click", function() {
        var id = $(this).attr('data-id');
        swal({
                title: "Are you sure?",
                text: "Do you want delete this project?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.post("manage/deletepro/delpro.php", {
                    id: id
                }).done(function(data) {
                    swal({
                        title: data,
                        text: "Project has been deleted.",
                        type: "success"
                    }, function() {
                        location.reload(true);
                    });
                });
            });
    });
    $(".container").on('click', ".infof", function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: 'manage/info/info.php',
            data: {
                id: id
            },
            success: function(data) {
                $(".container").html(data);
                $('.collapsible').collapsible();
                $('.modal').modal();
            }
        });

    });
    $(".member").on('click', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        $.ajax({
            url: 'manage/member/showmem.php',
            data: {
                id: id
            },
            success: function(data) {
                $(".container").html(data);
                $('.collapsible').collapsible();
                $('select').material_select();
                $.get("manage/member/getuserp.php", {
                    idj: id,
                    typ: "ad"
                }).done(function(data) {
                    $(".adminusem").html(data);
                });
                $(".droptrueallm").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled"
                });
                $(".dropuserp").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled",
                    receive: function() {
                        if ($("#deptpro").val() != null) {
                            var iduser = [];
                            var idj = $(this).attr('data-proid');
                            $(".userusem .userdatam").each(function(index, el) {
                                iduser[iduser.length] = $(this).attr('data-idu');
                            });
                            $.ajax({
                                url: 'manage/member/savemember.php',
                                type: 'POST',
                                data: {
                                    idj: idj,
                                    iddept: $("#deptpro").val(),
                                    iduser: JSON.stringify(iduser),
                                    typ: "us"
                                },
                                success: function(data) {}
                            });
                        } else {
                            swal("Warning", "Select Department Or Type Project", "warning");
                        }
                    },
                    remove: function() {
                        if ($("#deptpro").val() != null) {
                            var iduser = [];
                            var idj = $(this).attr('data-proid');
                            $(".userusem .userdatam").each(function(index, el) {
                                iduser[iduser.length] = $(this).attr('data-idu');
                            });
                            $.ajax({
                                url: 'manage/member/savemember.php',
                                type: 'POST',
                                data: {
                                    idj: idj,
                                    iddept: $("#deptpro").val(),
                                    iduser: JSON.stringify(iduser),
                                    typ: "us"
                                }
                            });
                        }
                    }
                });
                $(".dropadminp").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled",
                    receive: function() {
                        var iduser = [];
                        var idj = $(this).attr('data-proid');
                        $(".adminusem .userdatam").each(function(index, el) {
                            iduser[iduser.length] = $(this).attr('data-idu');
                        });
                        $.ajax({
                            url: 'manage/member/savemember.php',
                            type: 'POST',
                            data: {
                                idj: idj,
                                iddept: "101",
                                iduser: JSON.stringify(iduser),
                                typ: "ad"
                            },
                            success: function(data) {}
                        });
                    },
                    remove: function() {
                        var iduser = [];
                        var idj = $(this).attr('data-proid');
                        $(".adminusem .userdatam").each(function(index, el) {
                            iduser[iduser.length] = $(this).attr('data-idu');
                        });
                        $.ajax({
                            url: 'manage/member/savemember.php',
                            type: 'POST',
                            data: {
                                idj: idj,
                                iddept: "101",
                                iduser: JSON.stringify(iduser),
                                typ: "ad"
                            }
                        });
                    }
                });
                $(".dropdelm").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled",
                    receive: function() {
                        $(".dropdelm .userdatam").remove();
                    }
                });
                $(".droptrue,.droptrue2,.dropdel").disableSelection();
            }
        });
    });
    $(".memlogon").on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'manage/memberlogon/showmem.php',
            success: function(data) {
                $(".container").html(data);
                $('.collapsible').collapsible();
                $('select').material_select();
                $(".droptrue").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled"
                });
                $(".droptrue2").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled",
                    receive: function() {
                        if ($("#depmemlogon").val() != null) {
                            var iduser = [];
                            $(".useruse .userdata").each(function(index, el) {
                                iduser[iduser.length] = $(this).attr('data-idu');
                            });
                            $.ajax({
                                url: 'manage/memberlogon/savemember.php',
                                type: 'POST',
                                data: {
                                    iddept: $("#depmemlogon").val(),
                                    iduser: JSON.stringify(iduser),
                                    type: "us"
                                }
                            });
                        } else {
                            swal("Warning", "Select Department Project Or Level", "warning");
                        }
                    }
                });
                $(".droptrue3").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled",
                    receive: function() {
                        if ($("#depmemlogon").val() != null) {
                            var iduser = [];
                            $(".adminuse .userdata").each(function(index, el) {
                                iduser[iduser.length] = $(this).attr('data-idu');
                            });
                            $.ajax({
                                url: 'manage/memberlogon/savemember.php',
                                type: 'POST',
                                data: {
                                    iduser: JSON.stringify(iduser),
                                    type: "ad",
                                    iddept: $("#depmemlogon").val()
                                }
                            });
                        }
                    }
                });
                $(".dropdelmem").sortable({
                    connectWith: "tbody",
                    cancel: ".ui-state-disabled",
                    receive: function() {
                        $(".dropdelmem .userdata").each(function(index, el) {
                            var id = $(this).attr('data-idu');
                            $.get("manage/memberlogon/delmempro.php", {
                                id: id
                            }).done(function(data) {
                                console.log(data);
                            });
                            $(this).remove();
                        });
                    }
                });
                $(".droptrue,.droptrue2,.dropdel").disableSelection();
            }
        });
    });
    $(".dashboard").on('click', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'manage/dashboard/dashboard.php',
            data: {
                sort: "2"
            },
            success: function(data) {
                $(".container").html(data);
                $(".canvas").each(function() {
                    var chse = $(this);
                    var idp = $(this).attr('data-idp');
                    $.ajax({
                        url: 'manage/dashboard/datagraph.php',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            idj: idp
                        },
                        success: function(data) {
                            var cht = new Chart(chse, data);
                        }
                    });
                });
            }
        });
    });
    $(".departadd").on('click', function(event) {
        event.preventDefault();
        $.get("manage/department/adddepartment.php").done(function(data) {
            $(".container").html(data);
            $('.collapsible').collapsible();
        });
    });
    $(".task").on('click', function(event) {
        event.preventDefault();
        $.get("manage/task/dashtask.php", {
            id: $(this).attr('data-id')
        }).done(function(data) {
            $(".container").html(data);
            $('.collapsible').collapsible();
        });
    });
    $(".container").on('click', ".task", function(event) {
        event.preventDefault();
        $.get("manage/task/dashtask.php", {
            id: $(this).attr('data-id')
        }).done(function(data) {
            $(".container").html(data);
            $('.collapsible').collapsible();
        });
    });
    $(".container").on('click', '.addtk', function(event, id, idj) {
        event.preventDefault();
        id = (typeof id == "undefined") ? $(this).attr('data-idp') : id;
        idj = (typeof idj == "undefined") ? $(this).attr('data-idj') : idj;
        $.get("manage/task/shtask.php", {
            id: id,
            idj: idj
        }).done(function(data) {
            $(".container").html(data);
            $('.collapsible').collapsible();
            $('.modal').modal();
            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: 10
            });
            $('select').material_select();
        });
    });
    $(".container").on('submit', 'form[name=addtask]', function(event) {
        event.preventDefault();
        var fm = $(this),
            status = true;
        if ($("#name").val().length < 3) {
            swal("Warning", "Please Specify Subject", "warning");
        } else if ($("#str_date").val().lenght < 1 ||
            moment($("#str_date").val(), "YYYY-M-D").isBefore($("#str_date").attr('data-stps'), "YYYY-M-D") ||
            moment($("#str_date").val(), "YYYY-M-D").isAfter($("#end_date").attr('data-edps'), "YYYY-M-D") ||
            moment($("#str_date").val(), "YYYY-M-D").isAfter($("#end_date").val(), "YYYY-M-D")) {
            swal("Warning", "Please Check Start Date", "warning");
        } else if ($("#end_date").val().lenght < 1 ||
            moment($("#end_date").val(), "YYYY-M-D").isAfter($("#end_date").attr('data-edps'), "YYYY-M-D") ||
            moment($("#end_date").val(), "YYYY-M-D").isBefore($("#str_date").attr('data-stps'), "YYYY-M-D") ||
            moment($("#end_date").val(), "YYYY-M-D").isBefore($("#end_date").val(), "YYYY-M-D")) {
            swal("Warning", "Please Check End Date", "warning");
        } else if ($("#ownerps").val() == null) {
            swal("Warning", "Please Specify Owner", "warning");
        } else if ($("#assgnps").val().length < 1) {
            swal("Warning", "Please Select To Assign Task", "warning");
        } else if ($("#description").val().lenght < 1) {
            swal("Warning", "Please Specify Description", "warning");
        } else {
            if ($("#reminder").val().length > 0) {
                if (moment($("#reminder").val(), "YYYY-M-D").isBefore($("#str_date").attr('data-stps'), "YYYY-M-D") ||
                    moment($("#reminder").val(), "YYYY-M-D").isAfter($("#end_date").attr('data-edps'), "YYYY-M-D")) {
                    swal("Warning", "Please Check Reminder Date", "warning");
                    status = false;
                } else {
                    status = true;
                }
            }
            if (status) {
                $.ajax({
                        url: 'manage/task/savenewtask.php',
                        type: 'POST',
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: new FormData(fm[0])
                    })
                    .done(function(data) {
                        swal({
                            title: "Success",
                            text: data,
                            type: "success"
                        }, function() {
                            $('.modal').modal('close');
                            $(".addtk").trigger('click', fm.attr('data-idp'), fm.attr('data-idj'));
                        });
                    });
            }
        }
    });
    $(".container").on('click', '.uptk', function(event) {
        event.preventDefault();
        var el = $(this);
        $.get("manage/task/updatetaskview.php", {
            idc: el.attr('data-psid'),
            num: el.attr('data-id'),
            typeu: el.attr('data-type')
        }).done(function(data) {
            $("#uptask").html(data);
            $('.collapsible').collapsible();
            $('select').material_select();
            $('.datepicker').pickadate({
                selectMonths: true,
                selectYears: 10
            });
        });
    });
    $(".container").on('submit', 'form[name=uptask]', function(event) {
        event.preventDefault();
        var el = $(this),
            status = true;
        if ($("#nameu").val().length < 3) {
            swal("Warning", "Please Specify Subject", "warning");
        } else if ($("#str_dateu").val().lenght < 1 ||
            moment($("#str_dateu").val(), "YYYY-M-D").isBefore($("#str_dateu").attr('data-stps'), "YYYY-M-D") ||
            moment($("#str_dateu").val(), "YYYY-M-D").isAfter($("#end_dateu").attr('data-edps'), "YYYY-M-D") ||
            moment($("#str_dateu").val(), "YYYY-M-D").isAfter($("#end_dateu").val(), "YYYY-M-D")) {
            swal("Warning", "Please Check Start Date", "warning");
        } else if ($("#end_dateu").val().lenght < 1 ||
            moment($("#end_dateu").val(), "YYYY-M-D").isAfter($("#end_dateu").attr('data-edps'), "YYYY-M-D") ||
            moment($("#end_dateu").val(), "YYYY-M-D").isBefore($("#str_dateu").attr('data-stps'), "YYYY-M-D") ||
            moment($("#end_dateu").val(), "YYYY-M-D").isBefore($("#end_dateu").val(), "YYYY-M-D")) {
            swal("Warning", "Please Check End Date", "warning");
        } else if ($("#ownerpsu").val() == null) {
            swal("Warning", "Please Specify Owner", "warning");
        } else if ($("#assgnpsu").val().length < 1) {
            swal("Warning", "Please Select To Assign Task", "warning");
        } else if ($("#descriptionu").val().lenght < 1) {
            swal("Warning", "Please Specify Description", "warning");
        } else {
            if ($("#reminderu").val().length > 0) {
                if (moment($("#reminderu").val(), "YYYY-M-D").isBefore($("#str_dateu").attr('data-stps'), "YYYY-M-D") ||
                    moment($("#reminderu").val(), "YYYY-M-D").isAfter($("#end_dateu").attr('data-edps'), "YYYY-M-D")) {
                    swal("Warning", "Please Check Reminder Date", "warning");
                    status = false;
                } else {
                    status = true;
                }
            }
            if (status) {
                $.ajax({
                        url: 'manage/task/updatetask.php',
                        type: 'POST',
                        cache: false,
                        processData: false,
                        contentType: false,
                        data: new FormData(el[0])
                    })
                    .done(function(data) {
                        swal({
                            title: "Success",
                            text: data,
                            type: "success"
                        }, function() {
                            $('.modal').modal('close');
                            switch (el.attr('data-type')) {
                                case "1":
                                    $(".addtk").trigger('click', el.attr('data-idp'), el.attr('data-idj'));
                                    break;
                                case "2":
                                    $(".mytask").trigger('click');
                                    break;
                            }
                        });
                    });
            }
        }
    });
    $(".container").on('click', '.deltk', function(event) {
        event.preventDefault();
        var el = $(this);
        swal({
                title: "Are you sure?",
                text: "Do You Want Delete This Task?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.post("manage/task/deltask.php", {
                        idp: el.attr('data-psid'),
                        id: el.attr('data-id')
                    }).done(function(data) {
                        swal({
                            title: "Deleted!",
                            text: data,
                            type: "success"
                        }, function() {
                            $(".addtk").trigger('click', el.attr('data-psid'), el.attr('data-idj'));
                        });
                    });
                } else {
                    swal("Cancelled", "Your task is safe", "error");
                }
            });
    });
    $(".container").on('submit', 'form[name=adddepartmentf]', function(event) {
        event.preventDefault();
        $.post("manage/department/adddept.php", {
            id: $(this[0]).val()
        }).done(function(data) {
            swal({
                title: "Success",
                text: "Department added",
                type: "success"
            }, function() {
                $(".departadd").trigger('click');
            });
        });
    });
    $(".container").on('click', '.deldept', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-idd');
        swal({
                title: "Are you sure?",
                text: "Do you want to delete this Department?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function() {
                $.post("manage/department/deldept.php", {
                    id: id
                }).done(function(data) {
                    swal({
                        title: "Deleted!",
                        text: "Department has been deleted.",
                        type: "success"
                    }, function() {
                        $(".departadd").trigger('click');
                    });
                });
            });
    });
    $(".container").on("submit", "form[name=addprof]", function(e) {
        e.preventDefault();
        var fdata = new FormData(this);
        var iddata = $(this).attr("data-idpf");
        if ($("input[name=addpro]").val().length < 3) {
            swal("Warning", "Please Specify Process", "warning");
        } else {
            $.ajax({
                url: "manage/setting/setadd.php",
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                data: fdata,
                success: function(data) {
                    if (data === "Saved") {
                        swal("Success", data, "success");
                        $(".setting").trigger("click", iddata);
                    } else {
                        swal("Error", data, "error");
                    }
                }
            });
        }
    });
    $(".container").on("click", ".del", function(e) {
        e.preventDefault();
        var idproc = $(this).attr("data-del");
        swal({
                title: "Are you sure?",
                text: "Do You Want to Delete This Process?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete",
                closeOnConfirm: false
            },
            function() {
                $.ajax({
                    url: "manage/setting/del.php",
                    type: 'POST',
                    data: {
                        id: idproc
                    },
                    success: function(data) {
                        if (data === "SUC") {
                            swal("Success", "Done", "success");
                            $(".setting").trigger("click", $("form[name=addprof]").attr("data-idpf"));
                        } else {
                            swal("Error", "Can't Not Delete This Process", "error");
                        }
                    }
                });
            });
    });
    $(".container").on("submit", ".settingfm", function(e) {
        e.preventDefault();
        var idpr = $(this).attr("data-idpj");
        var svp = new FormData(this);
        if ($(".st" + idpr).val() === "" || moment($(".st" + idpr).val(), "YYYY-M-D").isBefore(moment($(".st" + idpr).attr("data-stpro"), "YYYY-M-D").format("YYYY-M-D")) || moment($(".st" + idpr).val(), "YYYY-M-D").isAfter(moment($(".et" + idpr).val(), "YYYY-M-D").format("YYYY-M-D"))) {
            swal("Warning", "Please Specify Date Start", "warning");
        } else if ($(".et" + idpr).val() === "" || moment($(".et" + idpr).val(), "YYYY-M-D").isAfter(moment($(".et" + idpr).attr("data-edpro"), "YYYY-M-D").format("YYYY-M-D")) || moment($(".et" + idpr).val(), "YYYY-M-D").isBefore(moment($(".st" + idpr).val(), "YYYY-M-D").format("YYYY-M-D"))) {
            swal("Warning", "Please Specify Date End", "warning");
        } else {
            $.ajax({
                url: "manage/setting/saveproc.php",
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                data: svp,
                success: function(data) {
                    if (data === "Saved") {
                        swal({
                            title: "Success",
                            text: "Done",
                            type: "success"
                        }, function() {
                            $(".setting").trigger("click", $("form[name=addprof]").attr("data-idpf"));
                        });
                    } else {
                        swal("Warning", data, "warning");
                    }
                }
            });
        }

    });
    $(".container").on("click", ".blockp", function(e) {
        e.preventDefault();
        var id = $(this).attr("data-idprocess");
        var idp = $(this).attr("data-idproject");
        $.ajax({
            url: "manage/setting/flowsetting.php",
            type: 'POST',
            data: {
                id: id,
                idp: idp
            },
            success: function(data) {
                $(".flowsetting").html(data);
                $("#flowsetting").modal();
                $('.collapsible').collapsible();
                $('select').material_select();
            }
        });
    });
});
$(".container").on("submit", ".relation", function(e) {
    e.preventDefault();
    $.ajax({
        url: 'manage/setting/flowsetsave.php',
        type: 'POST',
        processData: false,
        cache: false,
        contentType: false,
        data: new FormData($(".relation")[0]),
        success: function(data) {
            swal({
                title: data,
                text: "Done",
                type: "success"
            }, function() {
                $("#flowsetting").modal('close');
                $(".setting").trigger("click", $("form[name=addprof]").attr("data-idpf"));
            });
        }
    });
});
$(".container").on('click', '.delpro', function(event) {
    event.preventDefault();
    var id = $(this).attr('data-pointto');
    swal({
            title: "Are you sure?",
            text: "Do You Want to Delete This Relation?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete",
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                url: "manage/setting/delflowline.php",
                type: 'POST',
                data: {
                    id: id
                },
                success: function(data) {
                    if (data === "SUC") {
                        swal({
                            title: "Success",
                            text: "Done",
                            type: "success"
                        }, function() {
                            $("#flowsetting").modal('close');
                            $(".setting").trigger("click", $("form[name=addprof]").attr("data-idpf"));
                        });
                    } else {
                        swal({
                            title: "Error",
                            text: "Can't Not Delete This Process",
                            type: "error"
                        }, function() {
                            $("#flowsetting").modal('close');
                            $(".setting").trigger("click", $("form[name=addprof]").attr("data-idpf"));
                        });
                    }
                }
            });
        });
});
$(".container").on('click', '.detail', function(event) {
    event.preventDefault();
    /* Act on the event */
    var idj = $(this).attr('data-idproject'),
        idp = $(this).attr('data-idprocess');
    $.ajax({
        url: 'manage/view/procdetail.php',
        data: {
            idj: idj,
            idp: idp
        },
        success: function(data) {
            $(".modal-content").html(data);
            $('.collapsible').collapsible();
        }
    });
});
$(".container").on('click', '.detailtask', function(event) {
    event.preventDefault();
    var id = $(this).attr('data-psid');
    var type = $(this).attr('data-type');
    $.ajax({
        url: 'manage/info/detail.php',
        data: {
            id: id,
            type: type
        },
        success: function(data) {
            $(".modal-content").html(data);
            $('.collapsible').collapsible();
        }
    });
});
$(".container").on('change', '#department1', function(event) {
    event.preventDefault();
    var dep = $(this).val();
    $.ajax({
        url: 'manage/member/getuser.php',
        data: {
            dep: dep
        },
        success: function(data) {
            $(".dop").html(data);
        }
    });

});
$(".container").on('change', '#departmentlog', function(event) {
    event.preventDefault();
    var dep = $(this).val();
    $.ajax({
        url: 'manage/memberlogon/getuser.php',
        data: {
            dep: dep
        },
        success: function(data) {
            $(".dop").html(data);
        }
    });

});
$(".container").on('change', '#deptpro', function(event) {
    event.preventDefault();
    var idj = $(".userusem").attr('data-proid');
    var dept = $("#deptpro").val();
    if (dept != null) {
        $.ajax({
            url: 'manage/member/getuserp.php',
            data: {
                dept: dept,
                idj: idj,
                typ: "us"
            },
            success: function(data) {
                $(".userusem").html(data);
            }
        });
    }
});
$(".container").on('change', '#depmemlogon', function(event) {
    event.preventDefault();
    if ($("#depmemlogon").val() != null) {
        $.ajax({
            url: 'manage/memberlogon/getuserp.php',
            data: {
                dept: $("#depmemlogon").val(),
                type: "us"
            },
            success: function(data) {
                $(".useruse").html(data);
            }
        });
        $.ajax({
            url: 'manage/memberlogon/getuserp.php',
            data: {
                dept: $("#depmemlogon").val(),
                type: "ad"
            },
            success: function(data) {
                $(".adminuse").html(data);
            }
        });
    }
});
$(".container").on('change', 'input[name=sort]:checked', function(event) {
    event.preventDefault();
    var type = $(this).val();
    $.ajax({
            url: 'manage/dashboard/dashboard.php',
            data: {
                sort: type
            }
        })
        .done(function(data) {
            $(".container").html(data);
            $(".canvas").each(function(index, el) {
                var chse = $(this);
                var idp = $(this).attr('data-idp');
                $.ajax({
                    url: 'manage/dashboard/datagraph.php',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        idj: idp
                    },
                    success: function(data) {
                        var cht = new Chart(chse, data);
                    }
                });
            });
        });
});
$(".container").on('click', '.deplunch', function(event, idj) {
    event.preventDefault();
    var idj = (typeof idj == "undefined") ? $(this).attr('data-idj') : idj;
    $.get("manage/setting/depmanage.php", {
        idj: idj
    }).done(function(data) {
        $(".adddep").html(data);
        $('.collapsible').collapsible();
        $('select').material_select();
    });
});
$(".container").on('click', '.addnwdep', function(event) {
    event.preventDefault();
    var el = $(this);
    $.post("manage/setting/addsavedept.php", {
        dep: $("#adddepla").val(),
        idj: el.attr('data-idj')
    }).done(function(data) {
        swal({
            title: "Success",
            text: data,
            type: "success",
        }, function() {
            $(".deplunch").trigger('click', el.attr('data-idj'));
        });
    });
});
$(".container").on('click', '.delnwdep', function(event) {
    event.preventDefault();
    var el = $(this);
    swal({
            title: "Are you sure?",
            text: "Do you want delete this department?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function() {
            $.post("manage/setting/deldeppro.php", {
                idj: el.attr('data-idj'),
                idd: el.attr('data-idd')
            }).done(function(data) {
                swal({
                    title: "Success",
                    text: data,
                    type: "success",
                }, function() {
                    $(".deplunch").trigger('click', el.attr('data-idj'));
                });
            });
        });
});
$(".container").on('submit', '.editproj', function(event) {
    event.preventDefault();
    var el = $(this);
    if ($("#project_nameed").val().length < 1) {
        swal("Warning", "Please Specify Project Name", "warning");
    } else if ($("#ownered").val().length < 1) {
        swal("Warning", "Please Specify Owner", "warning");
    } else if ($("#Descriptioned").val().length < 1) {
        swal("Warning", "Please Specify Description", "warning");
    } else if (moment($("#due_date").val(), "YYYY-M-D").isBefore(moment($("#due_date").attr('data-due'), "YYYY-M-D"))) {
        swal("Warning", "Please Check Due Date Project", "warning");
    } else {
        $.ajax({
                url: 'manage/setting/updeatepj.php',
                type: 'POST',
                processData: false,
                cache: false,
                contentType: false,
                data: new FormData(el[0])
            })
            .done(function(data) {
                swal({
                    title: "Success",
                    text: data,
                    type: "success",
                }, function() {
                    location.reload(true);
                });
            });
    }
});
$(".container").on('click', '.delat', function(event) {
    event.preventDefault();
    var el = $(this);
    swal({
            title: "Are you sure?",
            text: "Do You Want To Delete This File?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete",
            closeOnConfirm: false
        },
        function() {
            $.post("manage/task/delfile.php", {
                fm: el.attr('data-file')
            }).done(function(data) {
                swal({
                    title: "Success",
                    text: data,
                    type: "success",
                }, function() {
                    $('.modal').modal('close');
                });
            });
        });
});
$(".dup").on('click', function(event) {
    event.preventDefault();
    var id = $(this).attr('data-id');
    $.get("manage/dupicate/form.php", {
        id: id
    }).done(function(data) {
        $(".dupm").html(data);
        $('.collapsible').collapsible();
        $('select').material_select();
        $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 10
        });
        $.getJSON('manage/setupflow/shname.php', function(json, textStatus) {
            $("#ownerdp").autocomplete({
                data: json
            });
        });
    });
});

$(".mytask").on('click', function(event) {
    event.preventDefault();
    $.get("manage/mytask/showtask.php").done(function(data) {
        $(".container").html(data);
        $('.collapsible').collapsible();
        $('.dropdown-button').dropdown();
        $('.modal').modal();
        $('select').material_select();
        $('.datepicker').pickadate({
            selectMonths: true,
            selectYears: 15
        });
    });
});

$(".container").on('click', '.quiuptk', function(event) {
    event.preventDefault();
    var el = $(this);
    $.post("manage/task/quickuptask.php", {
        per: el.attr('data-per'),
        id: el.attr('data-id'),
        psid: el.attr('data-psid')
    }).done(function(data) {
        swal({
            title: data,
            text: "Status has been updated",
            type: "success",
        },function() {
          $(".mytask").trigger('click');
        });
    });
});

function flow(id) {
    YUI().use('graphics', function(Y) {
        var mygraphic = new Y.Graphic({
            render: "#canvas"
        });
        var cv = mygraphic.addShape({
            type: "path",
            stroke: {
                weight: 4,
                color: "#849974"
            }
        });
        for (var polop of id) {
            for (var x = 0; x < polop.data.length; x++) {
                if (x == 0) {
                    cv.moveTo(polop.data[x].x, polop.data[x].y);
                } else {
                    cv.lineTo(polop.data[x].x, polop.data[x].y);
                }
            }
        }
        cv.end();
    });
}

setTimeout(function() {
    $(".dashboard").trigger('click');
}, 900);
