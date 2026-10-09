/** Native Media Library selection with accessible, explicit image ordering. */
( function () {
 'use strict';
 document.querySelectorAll( '.ddna-news-gallery' ).forEach( function ( root ) {
  const list = root.querySelector( '[data-news-gallery-list]' );
  const value = root.querySelector( '[data-news-gallery-value]' );
  const status = root.querySelector( '[data-news-gallery-status]' );
  function update() {
   const rows = Array.from( list.children );
   value.value = rows.map( row => row.dataset.imageId ).join( ',' );
   rows.forEach( function ( row, index ) {
    row.querySelector( '[data-news-gallery-up]' ).disabled = index === 0;
    row.querySelector( '[data-news-gallery-down]' ).disabled = index === rows.length - 1;
    row.querySelectorAll( 'button' ).forEach( button => button.setAttribute( 'aria-label', button.textContent + ' imagen ' + ( index + 1 ) ) );
   } );
   status.textContent = rows.length + ' imágenes en la galería.';
   value.dispatchEvent( new Event( 'change', { bubbles: true } ) );
  }
  root.querySelector( '[data-news-gallery-add]' ).addEventListener( 'click', function () {
   const frame = wp.media( { title: 'Agregar imágenes del carrusel', button: { text: 'Agregar imágenes' }, library: { type: 'image' }, multiple: true } );
   frame.on( 'select', function () {
    frame.state().get( 'selection' ).each( function ( model ) {
     const attachment = model.toJSON();
     if ( Array.from( list.children ).some( row => Number( row.dataset.imageId ) === attachment.id ) ) { return; }
     const row = document.createElement( 'li' ); row.dataset.imageId = attachment.id;
     const img = document.createElement( 'img' ); img.src = attachment.sizes?.thumbnail?.url || attachment.url; img.alt = attachment.alt || ''; img.style.cssText = 'width:80px;height:60px;object-fit:contain;vertical-align:middle;'; row.append( img );
     const label = document.createElement( 'span' ); label.textContent = attachment.title || attachment.filename; row.append( label );
     [ [ 'up', 'Subir' ], [ 'down', 'Bajar' ], [ 'remove', 'Quitar' ] ].forEach( function ( pair ) {
      const button = document.createElement( 'button' ); button.type = 'button'; button.className = 'button'; button.setAttribute( 'data-news-gallery-' + pair[0], '' ); button.textContent = pair[1]; row.append( button );
     } );
     list.append( row );
    } );
    update();
   } ); frame.open();
  } );
  list.addEventListener( 'click', function ( event ) {
   const button = event.target.closest( 'button' ); if ( ! button ) { return; }
   const row = button.closest( 'li' );
   if ( button.hasAttribute( 'data-news-gallery-up' ) && row.previousElementSibling ) { list.insertBefore( row, row.previousElementSibling ); }
   if ( button.hasAttribute( 'data-news-gallery-down' ) && row.nextElementSibling ) { list.insertBefore( row.nextElementSibling, row ); }
   if ( button.hasAttribute( 'data-news-gallery-remove' ) ) { row.remove(); root.querySelector( '[data-news-gallery-add]' ).focus(); }
   update();
  } );
  update();
 } );
}() );
