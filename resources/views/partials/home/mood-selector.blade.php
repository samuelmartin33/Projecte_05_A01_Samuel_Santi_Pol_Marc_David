<section style="padding:90px 48px 0;max-width:1480px;margin:0 auto;">
  <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;gap:24px;flex-wrap:wrap;">
    <div>
      <div class="mono" style="font-size:11px;color:var(--magenta);margin-bottom:12px;display:flex;align-items:center;gap:10px;">
        <span style="width:28px;height:1px;background:var(--magenta);display:inline-block;"></span>
        ¿Qué te apetece esta noche?
      </div>
      <h2 class="display" style="font-size:clamp(48px,6vw,96px);margin:0;">
        Pick your <em style="font-style:italic;color:var(--magenta);font-family:'Bebas Neue',sans-serif;">mood</em>.
      </h2>
    </div>
  </div>

  <div class="mood-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;">
    @foreach($moods as $mood)
      <button class="mood-card vibez-mood-card"
              data-mood="{{ $mood['id'] }}"
              onclick="vibezSelectMood('{{ $mood['id'] }}')"
              style="background:transparent;color:var(--ink);padding:28px 22px;text-align:left;cursor:pointer;display:flex;flex-direction:column;gap:14px;min-height:160px;border-radius:0;">
        <span style="font-size:38px;">{{ $mood['emoji'] }}</span>
        <div class="display" style="font-size:22px;line-height:1;">{{ $mood['label'] }}</div>
      </button>
    @endforeach
  </div>
</section>
