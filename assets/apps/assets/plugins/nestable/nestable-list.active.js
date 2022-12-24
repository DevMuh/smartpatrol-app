$(document).ready(function () {
    "use strict"; // Start of use strict
        //nestable
        var updateOutput = function (e)
        {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };
    
        // activate Nestable for list 1
        $('#nestable').nestable({
            group: 1,
            maxDepth: 2,
        }).on('change', function(e){
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            var source = list.nestable('serialize');
            if (Array.isArray(source)) {
                $('#floatGroupB').show()
                let temp = setSequence(source, 0, [])
                console.log(temp);
            }
        });

        function setSequence(data, parent, result) {
            data.forEach((element, i) => {
                var menu = {
                    id: element.id,
                    parent_id: parent,
                    sequence: i
                };
                result.push(menu)
                if (element.children != undefined) {
                    setSequence(element.children, element.id, result)
                }
            });
            return result;
        }
    
        // output initial serialised data
        updateOutput($('#nestable').data('output', $('#nestable-output')));
        updateOutput($('#nestable2').data('output', $('#nestable2-output')));
    
        $('#nestable-menu').on('click', function (e)
        {
            var target = $(e.target),
                    action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
    
        $('#nestable3').nestable();
    
    });