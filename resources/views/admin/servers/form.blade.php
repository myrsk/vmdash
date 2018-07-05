<div class="row mB-40">
  <div class="col-sm-8" id="wrapper">
    <div class="bgc-white p-20 bd">
      <div class="form-group">
        <label for="type">Provider Type  <span class="text-danger">*</span></label>
        <select class="form-control" name="type" id="type" required enab>
          <option value="">Please select ...</option>
        </select>
      </div>
      
      <div id="fields"></div>
    </div>  
  </div>

  <div class="col-sm-4">
    <div class="card" id="instructions">
      <div class="card-header">
        <i class="fa fa-info-circle"></i> <span class="title"></span>
      </div>
      <div class="card-body"></div>
    </div>
  </div>
</div>

@section('footer_scripts')
<script>
  var serverInfo = null;
  var countries = [];
  
  $(document).ready(function () {
    $.getJSON("/config/variables.json", function (vars) {
      $.each(vars.providers, function (type, name) {
        $("#type").append("<option value='" + type + "'" + (!serverInfo ? '' : (serverInfo.type == type ? ' selected' : '')) + ">" + name + "</option>");
      });

      $.each(vars.countries, function (abbr, name) {
        countries[abbr] = name;
      });
    });
    
    hideInstructions();

    $("#type").change(function() {
      if ($(this).val()) {
        showInstructions();
        loadProvider($(this).val());
      } else
        hideInstructions();
    });

    // Checks if the form is used for editing or creating a server.
    @isset($item)
      showInstructions();
      var serverInfo = {!! $item !!};
      loadProvider(serverInfo);
      $('#type').attr('disabled', 'disabled');
    @endisset
  });

  function hideInstructions() {
    $("#fields").html("");
    $("#instructions").hide();
    $("#wrapper").attr('class', 'col-sm-12');
  }

  function showInstructions() {
    $("#wrapper").attr('class', 'col-sm-8');
    $("#instructions").show();
  }

  function loadProvider(provider) {
    $.getJSON("/config/providers/" + (!provider.type ? provider : provider.type) + ".json", function (config) {
      $("#instructions .title").html(config.instructions.title);
      $("#instructions .card-body").html(config.instructions.body);

      $("#fields").html("");

      @isset($item)
      var serverInfo = {!! $item !!};
      $("#fields").append('<input type="hidden" id="type_hidden" name="type" value="" />');
      $("#type_hidden").val(serverInfo['type']);
      @endisset

      $.each(config.fields, function (name, options) {
        var input = "<div class='form-group'><label for='" + name + "'>" + options.label + (options.required ? ' <span class="text-danger">*</span>' : '') + "</label>";

        switch (options.type) {
          case "textarea":
            input += "<textarea class='form-control' rows='3' name='" + name + "' id='" + name + "' " + (options.required ? 'required' : '') + ">" + (!provider[name] ? '' : provider[name]) + "</textarea></div>";
            break;
          case "countries":
            input += "<select class='form-control' name='" + name + "' id='" + name + "' " + (options.required ? 'required' : '') + "><option value=''>Please select ...</option>";

            for (var country in countries) {
              input += "<option value='" + country + "'" + (!provider.location ? '' : (provider.location == country ? ' selected' : '')) + ">" + countries[country] + "</option>";
            }

            input += "</select></div>";
            break;
          default:
            input += "<input class='form-control' name='" + name + "' type='text' id='" + name + "' value='" + (!provider[name] ? '' : provider[name]) + "' " + (options.required ? 'required' : '') + "></div>";
            break;
        }

        $("#fields").append(input);
      });
    });
  }
</script>
@stop
