@extends('layouts.master') 

    @section('content') 
<body>
    
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KVN6NHG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

@include('layouts.menu')
<div class=innerbannerwrap>

@php
if((Request::get('_branch_name')))
$url  = "downloadapk?_branch_name=".$_REQUEST['_branch_name'];
else
$url  = "downloadapk?_branch_name=010O";
@endphp
     

    
        <div class=innerbannerinstall>
            <h2 class=bannerHeadline>
                <h2 style="color:#ffffff; display: block;">{{$content->title??'Page not found'}}</h2>
            </h2>
            
        </div>

    </div>
    
    @if($content->slug!="contact-us" && $content->slug != "contact_us")
  
    <section class>
    
        <div class=container>
            <div class=row>

                <p class=headParagraph> {!!$content->page_content!!}</p>

                
            </div>
        </div>
        <br>
    </section>
    @else
  
    <section class=innerpage_all_wrap>

        <div class=container>
            <div class=row>
                <div class="innerWrapper">
                <div class="col-md-12 text-center">
                    <h2 class="contct-heading">Get In Touch With Us<h2>
                </div>
           <ul class="contact_icon clearfix">
            
            <div class="col-md-4">

            <li><a><i class="fa fa-phone">
              </i> <span>{{$settings->mobile}}</span></a></li>
                    
                </div>


                <div class="col-md-4">
                    <li><a href=#><i class="fa fa-map-marker"></i> <span>{{$settings->company_address}}</span></a>
                        </li>
                </div>

                <div class="col-md-4">
                    <li><a href=#><i class="fa fa-map-marker"></i> <span>{{$settings->company_address}}</span></a>
                        </li>
           
                    
                </div>
</ul>

   </div> 
               
            </div>
        </div>
    </section>
    @endif
  
    @stop



   

                
                   
                      

                        
             