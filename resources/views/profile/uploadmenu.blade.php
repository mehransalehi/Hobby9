<div id="fileinfodiv" class="col-xs-12">
  <form class="form-horizontal" id="myfrm" enctype="multipart/form-data">
    <div class="form-content-1 col-sm-6">
        <fieldset>
          <div class="form-group row requird-field">
            <label class="control-label">عنوان</label>
            <input maxlength="" class="form-control input-sm" name="title" type="text" required="required"/>
            <p class="well">در این قسمت عنوان اصلی رسانه وارد شود.</p>
          </div>
          <div class="form-group row">
            <label class="control-label">خالق</label>
            <input maxlength="" class="form-control input-sm" type="text" name="author"/>
            <p class="well">در این قسمت نام خالق اثر در صورت وجود ذکر شود.</p>
          </div>
          <div class="form-group row">
            <label class="control-label">ناشر</label>
            <input class="form-control input-sm" maxlength="" name="publisher" type="text"/>
            <p class="well">در این قسمت نام شرکت,سازمان و یا فردی که این اثر را منتشر کرده است را وارد کنید.</p>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">تگ ها</label>
            <input class="form-control input-sm" name="tag" maxlength="" type="text" required="required"/>
            <p class="well">در این قسمت باید کلمات کلیدی مربوط به این رسانه وارد شود. با این کار رسانه شما به سهولت در دسترس است. با انتخاب کلمات کلیدی مناسب رسانه شما در متور های جستجو و جستوجی سایت به راحتی توسط دیگر کاربران پیدا می شود.برای وارد کردن تگ ها کلمات را با خط تیره (-) از هم جدا کنید به عنوان مثال برای رسانه ای با موضوع آموزش رایانه تگها می توانند به صورت زیر باشند :<br>آموزش رایانه-آموزشی-کامپیوتر</p>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">زبان</label>
            <select class="form-control input-sm" name="language" required="required">
              <option>فارسی</option>
              <option>غيرفارسی</option>
            </select>
            <p class="well">زبان این رسانه را انتخاب کنید.</p>
          </div>
          <div class="form-group row requird-field">
            <label class="control-label">دسته</label>
            <select class="form-control input-sm" name="category" required="required">
              <option value="">دسته را انتخاب کنید.</option>
              @foreach(config('co.categorys') as $cat)
                <option value="{{$cat}}">{{$cat}}</option>
              @endforeach
            </select>
            <p class="well">دسته ای که رسانه در آن قرار دارد.</p>
          </div>
          <div class="form-group row">
            <label class="control-label">نظر دهی</label>
            <select class="form-control input-sm" name="comment">
              <option value="no">خیر وجود نداشته باشد</option>
              <option value="yes" selected="selected">بله وجود داشته باشد</option>
            </select>
            <p class="well">آیا قابلیت گذاشتن نظر برای کاربران وجود داشته باشد؟</p>
          </div>
        </fieldset>
    </div>
    <div class="form-content-2 col-sm-6">
        <fieldset>
          <div class="form-group row">
            <label class="control-label">دسترسی خارج سایت</label>
            <select class="form-control input-sm" name="ath">
              <option value="no">خیر وجود نداشته باشد</option>
              <option value="yes" selected="selected">بله وجود داشته باشد</option>
            </select>
            <p class="well">آیا قابلت نمایش این رسانه در خارج از سایت وجود داشته باشد؟</p>
          </div>
          <div class="form-group row">
            <label class="control-label">طبقه ها</label>
            <select class="form-control input-sm" name="fabranch">
            <option selected="selected">بدون طبقه</option>
            @if(@$data['branches'])
              @foreach($data['branches'] as $branch)
                <option value="{{$branch->hash}}">{{$branch->name}}</option>
              @endforeach
            @endif
          </select>
            <p class="well">طبقه ای که تمایل دارید این رسانه در آن قرار بگیرد.</p>
          </div>
          <div id="img-upload-input" class="form-group row">
          </div>
          <div class="form-group row">
            <label class="control-label">رسانه شاخص</label>
            <input class="form-control input-sm" name="top_media" value="set" type="checkbox"/>
            <p class="well">در صورت انتخاب این رسانه در هنگام مشاهده لیست رسانه های شما توسط کاربران به صورت شاخص و در بالای لیست به نمایش در خواهد آمد.</p>
          </div>
          <div class="form-group row">
            <label class="control-label">توضیحات</label>
            <textarea class="form-control" maxlength="" rows="12" name="des"></textarea>
            <p class="well">در این قسمت توضیحات مربوط به این رسانه را وارد کنید.</p>
          </div>
          <div class="form-group row">
            <input id="btnsubmit" class="btn btn-success btnsub" onclick="detail_submition('myfrm','upload');" value="ارسال اطلاعات"/>
            <input id="hash" name="hash" type="hidden" value="null"/>
          </div>
        </fieldset>
      </div>
    </form>
  </form>
</div>
