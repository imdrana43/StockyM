 <template>
  <div class="footer_wrap">
    <!-- Footer Start -->
    <!-- <div class="flex-grow-1"></div> -->
    <div class="app-footer">
      <!-- <div class="row">
        <div class="col-md-9">
          <p><strong>{{currentUser.footer}}</strong></p>
        </div>
      </div> -->
      <div
        class="footer-bottom d-flex flex-column flex-sm-row align-items-center"
      >
        <div class="d-flex align-items-center">
          <!-- <img class="logo" :src="'/images/'+currentUser.logo" alt width="60" height="60"> -->
          <div>
            <div>
              <p class="m-0">&copy; {{ new Date().getFullYear() }} {{$t('developed_by')}}  <a href="https://fb.com/r.h.rana77" target="_blank">RH Rana</a></p>
              <!-- <p class="m-0">All rights reserved - v4.0.6</p> -->
            </div>
          </div>
          <!-- <span class="flex-grow-1"></span> -->
        </div>
      </div>
      <!-- fotter end -->
    </div>
  </div>
</template> 
<script>
import { mapGetters, mapActions } from "vuex";

export default {
  data() {
    return {};
  },
  computed: {
     ...mapGetters([
       "currentUser",
    ]),
  },

  methods: {}
};
</script>


<style lang="scss" scoped>
</style> 