$.fn.dataTableExt.oPagination.page_select_with_ellipses = {

    "fnInit": function (oSettings, nPaging, fnCallbackDraw) {
        $(nPaging).prepend($("<ul class=\"pagination\"></ul>"));
        var ul = $("ul", $(nPaging));
        nFirst = document.createElement('li');
        nPrevious = document.createElement('li');
        nNext = document.createElement('li');

        $(nPrevious).append($('<a>' + (oSettings.oLanguage.oPaginate.sPrevious) + '</a>'));
        $(nFirst).append($('<a>1</a>'));
        $(nNext).append($('<a>' + (oSettings.oLanguage.oPaginate.sNext) + '</a>'));

        nFirst.className = "paginate_button first active";
        nPrevious.className = "paginate_button previous";
        nNext.className = "paginate_button next";

        ul.append(nPrevious);
        ul.append(nFirst);
        ul.append(nNext);

        $(nFirst).click(function () {
            oSettings.oApi._fnPageChange(oSettings, "first");
            fnCallbackDraw(oSettings);
        });

        $(nPrevious).click(function () {
            if (!(oSettings._iDisplayStart === 0)) {
                oSettings.oApi._fnPageChange(oSettings, "previous");
                fnCallbackDraw(oSettings);
            }
        });

        $(nNext).click(function () {
            if (!(oSettings.fnDisplayEnd() == oSettings.fnRecordsDisplay()
                    ||
                    oSettings.aiDisplay.length < oSettings._iDisplayLength)) {
                oSettings.oApi._fnPageChange(oSettings, "next");
                fnCallbackDraw(oSettings);
            }
        });

        /* Disallow text selection */
        $(nFirst).bind('selectstart', function () { return false; });
        $(nPrevious).bind('selectstart', function () { return false; });
        $(nNext).bind('selectstart', function () { return false; });

        // Reset dynamically generated pages on length/filter change.
        $(oSettings.nTable).DataTable().on('length.dt', function (e, settings, len) {
            $("li.dynamic_page_item", nPaging).remove();
        });

        $(oSettings.nTable).DataTable().on('search.dt', function (e, settings, len) {
            $("li.dynamic_page_item", nPaging).remove();
        });

        ul.append($("<div style='display: inline-block; margin: 6px 10px 0 10px'></div>"));
        var div = $("div", ul);

        var nInput = document.createElement('select');
        var nPage = document.createElement('span');
        var nOf = document.createElement('span');
        nOf.className = "paginate_of";
        nPage.className = "paginate_page";
        if (oSettings.sTableId !== '') {
            nPaging.setAttribute('id', oSettings.sTableId + '_paginate');
        }
        nInput.style.display = "inline";
        nPage.innerHTML = "Page ";

        div.append(nPage);
        div.append(nInput);
        div.append(nOf);
        $(nInput).change(function (e) { // Set DataTables page property and redraw the grid on listbox change event.
            window.scroll(0,0); //scroll to top of page
            if (this.value === "" || this.value.match(/[^0-9]/)) { /* Nothing entered or non-numeric character */
                return;
            }
            var iNewStart = oSettings._iDisplayLength * (this.value - 1);
            if (iNewStart > oSettings.fnRecordsDisplay()) { /* Display overrun */
                oSettings._iDisplayStart = (Math.ceil((oSettings.fnRecordsDisplay() - 1) / oSettings._iDisplayLength) - 1) * oSettings._iDisplayLength;
                fnCallbackDraw(oSettings);
                return;
            }
            oSettings._iDisplayStart = iNewStart;
            fnCallbackDraw(oSettings);
        }); /* Take the brutal approach to cancelling text selection */
        $('span', nPaging).bind('mousedown', function () {
            return false;
        });
        $('span', nPaging).bind('selectstart', function () {
            return false;
        });
    },


    /*
     * Function: oPagination.simple_incremental_bootstrap.fnUpdate
     * Purpose:  Update the list of page buttons shows
     * Inputs:   object:oSettings - dataTables settings object
     *           function:fnCallbackDraw - draw function which must be called on update
     */
    "fnUpdate": function (oSettings, fnCallbackDraw) {
        if (!oSettings.aanFeatures.p) {
            return;
        }

        /* Loop over each instance of the pager */
        var an = oSettings.aanFeatures.p;

        var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);

        var buttons = an[0].getElementsByTagName('li');
        $(buttons).removeClass("active");

        if (oSettings._iDisplayStart === 0) {
            buttons[0].className = "paginate_buttons disabled previous";
            buttons[buttons.length - 1].className = "paginate_button enabled next";
        } else {
            buttons[0].className = "paginate_buttons enabled previous";
        }

        var page = Math.round(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;

        while(buttons.length > 2) {
            buttons[1].remove();
        }

        var numbers = $.fn.DataTable.ext.pager._numbers(page - 1, iPages);

        numbers.forEach(function (currentValue, index) {

            var newElement = '';
            if (currentValue === 'ellipsis')
            {
                newElement = $('<li class="dynamic_page_item"><span class="ellipsis">&#x2026;</span></li>');
                $(buttons[buttons.length - 1]).before(newElement);
                buttons[buttons.length - 2].className = "paginate_button disabled";
            }
            else
            {
                var active = '';

                if (page === (currentValue + 1))
                {
                    active = 'active';
                }

                newElement = $('<li class="dynamic_page_item ' + active + '"><a>' + (currentValue + 1) + "</a></li>");
                $(buttons[buttons.length - 1]).before(newElement);

                newElement.click(function () {
                    $(oSettings.nTable).DataTable().page(currentValue);

                    fnCallbackDraw(oSettings);
                });
            }
        });



        if (oSettings.fnDisplayEnd() == oSettings.fnRecordsDisplay()
            ||
            oSettings.aiDisplay.length < oSettings._iDisplayLength) {
            buttons[buttons.length - 1].className = "paginate_button disabled next";
        }


        var spans = an[0].getElementsByClassName('paginate_of');
        var inputs = an[0].getElementsByTagName('select');
        var elSel = inputs[0];
        if(elSel.options.length != iPages) {
            elSel.options.length = 0; //clear the listbox contents
            for (var j = 0; j < iPages; j++) { //add the pages
                var oOption = document.createElement('option');
                oOption.text = j + 1;
                oOption.value = j + 1;
                try {
                    elSel.add(oOption, null); // standards compliant; doesn't work in IE
                } catch (ex) {
                    elSel.add(oOption); // IE only
                }
            }
            spans[0].innerHTML = "&nbsp;of&nbsp;" + iPages;
        }
        elSel.value = page;
    }
};