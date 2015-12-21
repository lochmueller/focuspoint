Examples
--------

This is a collection of examples provided by github issues.
Thanks for contributing and sharing.


Responsive Header IMG with pure CSS
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Contributed by Martin Zarth (www.zarthwork.de).
The header files are integrated inwards the page relations in three different sizes. Additional CSS for the image container has been set to:

.. code-block:: css

	.bgimg {
		width: 100%;
		height: 600px;
		display: block;
	}

The Integration is done by TS inlineCSS like so:

.. code-block:: typoscript

	page.cssInline {

	    // needed to let dataWrap
	    // recognize curly brackets
	    1 = LOAD_REGISTER
	    1.curl1 = {
	    1.curl2 = }

	    // <source>
	    10 = FILES
	    10 {

	        // get the reference from pages > media
	        references {
	            table = pages
	            uid.data = page:uid
	            fieldName = media
	        }

	        renderObj = IMG_RESOURCE
	        renderObj {

	            // import file
	            file.import.data = file:current:uid
	            file.treatIdAsReference = 1

	          stdWrap.dataWrap.cObject = CASE
	          stdWrap.dataWrap.cObject {

	            // get files by sorting num
	            key.data = register:FILE_NUM_CURRENT

	            // sm
	            0 = TEXT
	            0.value = background-image: url(|);
	            0.postCObject = TEXT
	            0.postCObject.value = background-position: left {fp:xp_positive}% top {fp:yp_positive}%;
	            0.postCObject.if.isTrue.data = fp:xp
	            0.wrap = .bgimg {register:curl1} | {register:curl2}

	            // md
	            1 < .0
	            1.outerWrap = @media only screen and (min-width: 600px) {register:curl1} | {register:curl2}

	            // lg
	            2 < .0
	            2.outerWrap = @media only screen and (min-width:1024px) {register:curl1} | {register:curl2}

	          }
	          stdWrap.wrap = /|

	      }

	        begin = 0
	        maxItems = 3

	        stdWrap.outerWrap (
	            /* background-image with focus point*/
	                |
	            /* */
	        )

	    }

	}
