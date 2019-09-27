import { TestBed } from '@angular/core/testing';

import { ArticoliService } from './articoli.service';

describe('ArticoliService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: ArticoliService = TestBed.get(ArticoliService);
    expect(service).toBeTruthy();
  });
});
