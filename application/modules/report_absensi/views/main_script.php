<script>
    const globalDocumentInnerHeight = $(document).innerHeight();
    $(document).ready(function(){
        handleDynamicWidthHeight2()
    })

    function handleDynamicWidthHeight2() {
        const styleEl = document.createElement('style')
        document.head.appendChild(styleEl)
        const styleSheetEl = styleEl.sheet
        styleSheetEl.insertRule(`#card-body { height: ${globalDocumentInnerHeight-230}px !important; }`)
    }
</script>