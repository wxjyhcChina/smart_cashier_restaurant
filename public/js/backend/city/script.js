//$(function() {
//    $("#city_name").citypicker();
//});

$('#city_name').bind('cp:updated', function() {
    //获取省份的值
    var province=$("#city_name").next().find('span[data-count="province"]').attr('data-code');
    //获取城市的值
    var city=$("#city_name").next().find('span[data-count="city"]').attr('data-code');
    //获取区县的值
    var district=$("#city_name").next().find('span[data-count="district"]').attr('data-code');

    if(district)
    {
        $('#ad_code').val(district);
    }
    else if(city)
    {
        $('#ad_code').val(city);
    }
    else
    {
        $('#ad_code').val(province);
    }

});