class Course{

    static init(){
      $('#courses-data-table').hide();
      $('.btn.add-course').click(function (){
        $('#modalLabel').html('Add course');
        $('.modal-footer button[type="submit"]').html('Add course');
      });
      $("#add-course").validate({
        submitHandler: function(form, event) {
          event.preventDefault();
          var data = AUtils.form2json($(form));
          $('#courses-data-table').hide();
          if (data.id){
            Course.update(data);
          }else{
            Course.add(data);
          }
        }
      });
      AUtils.role_based_elements();
      Course.get_all();

    }    
  
    static get_all(){
      $("#courses-data-table").DataTable({
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
          url: "api/admin/courses",
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
            //console.log(d);
          }
        },
        columns: [
              { "data": "id",
                "render": function ( data, type, row, meta ) {
                  return '<div class="course-id-field" style="min-width: 60px;"> <span class="badge">'+data+'</span><div><a class="pull-right" style="font-size: 15px; cursor: pointer;" onclick="Course.pre_edit('+data+')"><i class="edit-icon"></i></a><a class="pull-left" style="font-size: 15px; cursor: pointer;" onclick="Course.delete('+data+')"><i class="delete-icon"></i></a></div>  </div>';
                }
              },
              { "data": "name" },              
              { "data": "description" }
          ]
      });
      $('#courses-data-table').show();
    }
  
    static add(course){
      RestClient.post("api/admin/courses", course, function(data){        
        toastr.success("Course has been added");
        Course.get_all();
        $("#add-course").trigger("reset");
        $('#add-course-modal').modal("hide");
      });
    }
  
    static update(course){
      RestClient.put("api/admin/courses/"+course.id, course, function(data){
        toastr.success("Course has been updated");
        Course.get_all();
        $("#add-course").trigger("reset");
        $("#add-course *[name='id']").val("");
        $('#add-course-modal').modal("hide");
      });
    }
  
    static pre_edit(id){
      RestClient.get("api/admin/courses/"+id, function(data){
        AUtils.json2form("#add-course", data);
        $('#modalLabel').html('Update course');
        $('.modal-footer button[type="submit"]').html('Update course');
        $("#add-course-modal").modal("show");
      });
    }
    static delete(id){
      RestClient.put("api/admin/courses/"+id,{ status: 'inactive'}  , function(data){            
        toastr.success("Course has been deleted");
        Course.get_all();
      });
    }
  }
  