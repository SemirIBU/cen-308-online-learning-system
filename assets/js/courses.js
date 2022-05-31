class Course{

    static init(){
      $("#add-course").validate({
        submitHandler: function(form, event) {
          event.preventDefault();
          var data = AUtils.form2json($(form));
          if (data.id){
            Course.update(data);
          }else{
            Course.add(data);
          }
        }
      });
      AUtils.role_based_elements();
      Course.get_all();
      Course.chart();
    }
  
    static chart(){
      RestClient.get("api/user/courses_chart", function(chart_data){
        new Morris.Line({
          element: 'course-chart-container',
          data: chart_data,
          xkey: 'year',
          ykeys: ['value'],
          labels: ['Value']
        });
      });
    }
  
    static get_all(){
      $("#courses-data-table").DataTable({
        order: [[ 0, "desc" ]],
        processing: true,
        serverSide: true,
        bDestroy: true,
        pagingType: "simple",
        preDrawCallback: function( settings ) {
          if (settings.aoData.length < settings._iDisplayLength){
            //disable pagination
            settings._iRecordsTotal=0;
            settings._iRecordsDisplay=0;
          }else{
            //enable pagination
            settings._iRecordsTotal=100000000;
            settings._iRecordsDisplay=1000000000;
          }
        },
        responsive: true,
        language: {
              "zeroRecords": "Nothing found - sorry",
              "info": "Showing page _PAGE_",
              "infoEmpty": "No records available",
              "infoFiltered": ""
        },
        ajax: {
          url: "api/user/courses",
          type: "GET",
          beforeSend: function(xhr){
            xhr.setRequestHeader('Authentication', localStorage.getItem("token"));
          },
          dataSrc: function(resp){
            return resp;
          },
          data: function ( d ) {
            d.offset=d.start;
            d.limit=d.length;
            d.search = d.search.value;
            d.order = encodeURIComponent((d.order[0].dir == 'asc' ? "-" : "+")+d.columns[d.order[0].column].data);
            delete d.start;
            delete d.length;
            delete d.columns;
            delete d.draw;
            console.log(d);
          }
        },
        columns: [
              { "data": "id",
                "render": function ( data, type, row, meta ) {
                  return '<div style="min-width: 60px;"> <span class="badge">'+data+'</span><a class="pull-right" style="font-size: 15px; cursor: pointer;" onclick="Course.pre_edit('+data+')"><i class="fa fa-edit"></i></a> </div>';
                }
              },
              { "data": "name" },              
              { "data": "description" }
          ]
      });
    }
  
    static add(email_template){
      RestClient.post("api/user/courses", email_template, function(data){
        toastr.success("Course has been added");
        Course.get_all();
        $("#add-course").trigger("reset");
        $('#add-course-modal').modal("hide");
      });
    }
  
    static update(email_template){
      RestClient.put("api/user/courses/"+email_template.id, email_template, function(data){
        toastr.success("Course has been updated");
        Course.get_all();
        $("#add-course").trigger("reset");
        $("#add-course *[name='id']").val("");
        $('#add-course-modal').modal("hide");
      });
    }
  
    static pre_edit(id){
      RestClient.get("api/user/courses/"+id, function(data){
        AUtils.json2form("#add-course", data);
        $("#add-course-modal").modal("show");
      });
    }
  }
  